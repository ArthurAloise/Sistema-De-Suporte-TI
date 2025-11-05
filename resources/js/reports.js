import jsPDF from "jspdf";
import html2canvas from "html2canvas";
import {
    Chart,
    BarController,
    BarElement,
    LineController,
    LineElement,
    DoughnutController,
    PieController,
    CategoryScale,
    LinearScale,
    PointElement,
    ArcElement,
    Tooltip,
    Legend,
    TimeScale,
} from "chart.js";

Chart.register(
    BarController,
    BarElement,
    LineController,
    LineElement,
    DoughnutController, // ← REGISTRE AQUI
    PieController, // ← REGISTRE AQUI
    CategoryScale,
    LinearScale,
    PointElement,
    ArcElement,
    Tooltip,
    Legend,
    TimeScale
);

const qs = (sel) => document.querySelector(sel);
const qsa = (sel) => Array.from(document.querySelectorAll(sel));

/* ===== Helpers de período ===== */
function iso(d) {
    return d.toISOString().slice(0, 10);
}

function rangeFromPeriod(period) {
    if (!period) return {};
    const now = new Date();
    const to = iso(now);
    const start = new Date(now);
    switch (period) {
        case "7d":
            start.setDate(now.getDate() - 6);
            break;
        case "30d":
            start.setDate(now.getDate() - 29);
            break;
        case "90d":
            start.setDate(now.getDate() - 89);
            break;
        case "12m":
            start.setMonth(now.getMonth() - 11);
            start.setDate(1);
            break;
        default:
            return {};
    }
    return { date_from: iso(start), date_to: to };
}

/* ===== Coleta filtros ===== */
function paramsFromFilters() {
    const s = new URLSearchParams();
    const gv = (id) => qs(id)?.value || "";

    const status = gv("#f_status");
    const priority = gv("#f_priority");
    const category = gv("#f_category");
    const type = gv("#f_type");
    const period = gv("#f_period");
    let from = gv("#f_from");
    let to = gv("#f_to");

    if (period && (!from || !to)) {
        const r = rangeFromPeriod(period);
        from = from || r.date_from || "";
        to = to || r.date_to || "";
    }

    if (status) s.set("status", status);
    if (priority) s.set("priority", priority);
    if (category) s.set("category_id", category);
    if (type) s.set("type_id", type);
    if (from) s.set("date_from", from);
    if (to) s.set("date_to", to);
    if (period) s.set("period", period);

    return s;
}

/* ===== Ajax helpers (robusto) ===== */
async function jgetWithParams(path, params) {
    const base = window.location.origin;
    const url = new URL(path, base);
    params.forEach((v, k) => {
        if (v) url.searchParams.set(k, v);
    });

    const res = await fetch(url, { headers: { Accept: "application/json" } });
    const text = await res.text();

    // Se não for 2xx, loga e retorna [] para não quebrar os gráficos
    if (!res.ok) {
        console.error(`[${path}] HTTP ${res.status}:`, text.slice(0, 200));
        return [];
    }

    try {
        return JSON.parse(text);
    } catch (e) {
        console.error(`[${path}] erro/fallback:`, e, text.slice(0, 200));
        return [];
    }
}

/* ===== Utils p/ datasets ===== */
function toDataset(rows, labelKey = "label", valueKey = "total") {
    return {
        labels: rows.map((r) => r[labelKey]),
        data: rows.map((r) => Number(r[valueKey]) || 0),
    };
}
function rankTop(rows, labelKey = "label", valueKey = "total", topN = 10) {
    const ord = [...rows]
        .sort((a, b) => (b[valueKey] || 0) - (a[valueKey] || 0))
        .slice(0, topN);
    return toDataset(ord, labelKey, valueKey);
}

/* ===== Chart manager ===== */
const charts = {};
function renderOrUpdate(id, cfg) {
    const ctx = qs(id)?.getContext("2d");
    if (!ctx) return;
    if (charts[id]) charts[id].destroy();
    charts[id] = new Chart(ctx, cfg);
}

/* ===== Paleta simples ===== */
function palette(n = 10) {
    const base = [
        "#2A65D0",
        "#0EA5E9",
        "#22C55E",
        "#F59E0B",
        "#EF4444",
        "#8B5CF6",
        "#14B8A6",
        "#E11D48",
        "#A3E635",
        "#6366F1",
    ];
    return Array.from({ length: n }, (_, i) => base[i % base.length]);
}

/* ===== Links de export ===== */
function applyParamsToLink(aEl, basePath, params) {
    const url = new URL(basePath, window.location.origin);
    params.forEach((v, k) => {
        if (v) url.searchParams.set(k, v);
    });
    aEl.href = url.toString();
}
/* ===== Função para Gerar PDF (Versão Multi-página) ===== */
async function generatePdf() {
    const btnPdf = qs("#btnExportPDF");
    const content = qs("#report-content");

    if (!content || !btnPdf) {
        console.error("Elemento de conteúdo ou botão do PDF não encontrado.");
        return;
    }

    btnPdf.setAttribute("disabled", true);
    btnPdf.textContent = "Atualizando dados...";

    try {
        // Espera os dados carregarem completamente
        await loadAll();

        btnPdf.textContent = "Gerando PDF...";

        const canvas = await html2canvas(content, {
            scale: 2, // Mantém a alta qualidade
            // Garante que o html2canvas capture toda a altura do elemento, mesmo o que está fora da tela
            windowHeight: content.scrollHeight,
        });

        const imgData = canvas.toDataURL("image/png");

        // Dimensões da imagem capturada, convertidas para a proporção do PDF
        const pdf = new jsPDF("p", "mm", "a4");
        const page_width = pdf.internal.pageSize.getWidth();
        const page_height = pdf.internal.pageSize.getHeight();
        const img_width = canvas.width;
        const img_height = canvas.height;
        const ratio = img_width / img_height;

        const pdf_img_height = page_width / ratio;
        let height_left = pdf_img_height;
        let position = 0;

        // Adiciona a primeira página
        pdf.addImage(imgData, "PNG", 0, position, page_width, pdf_img_height);
        height_left -= page_height;

        // Adiciona novas páginas enquanto houver conteúdo
        while (height_left > 0) {
            position = -height_left; // Move a "janela de visualização" da imagem para baixo
            pdf.addPage();
            pdf.addImage(
                imgData,
                "PNG",
                0,
                position,
                page_width,
                pdf_img_height
            );
            height_left -= page_height;
        }

        pdf.save("relatorio-de-tickets.pdf");
    } catch (error) {
        console.error("Erro ao gerar o PDF:", error);
        alert("Ocorreu um erro ao gerar o PDF. Tente novamente.");
    } finally {
        // Reativa o botão
        btnPdf.removeAttribute("disabled");
        btnPdf.textContent = "Gerar PDF";
    }
}
/* ===== Carregamento principal ===== */
async function loadAll() {
    const p = paramsFromFilters();
    const btnApply = qs("#btnApply");

    // Mostra um feedback de carregamento
    if (btnApply) {
        btnApply.setAttribute("disabled", true);
        btnApply.textContent = "Carregando...";
    }

    try {
        // KPIs
        {
            const data = await jgetWithParams(
                "/admin/reports/api/tickets/kpis",
                p
            );
            const map = {
                total: "total",
                abertos: "abertos",
                abertosperiodo: "openInPeriod",
                resolvidos: "resolvidos",
                overdue: "overdue",
                sla: "slaRate",
                mttrh: "mttrHours",
            };
            qsa("[data-kpi]").forEach((el) => {
                const key = el.getAttribute("data-kpi");
                const val = data[map[key]] ?? "—";
                el.textContent = val === null || val === undefined ? "—" : val;
            });
        }

        // Charts Tickets
        {
            let rows = await jgetWithParams(
                "/admin/reports/api/tickets/by-status",
                p
            );
            let { labels, data } = toDataset(rows, "status", "total");
            renderOrUpdate("#ch_status", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/by-priority",
                p
            );
            ({ labels, data } = toDataset(rows, "label", "total"));
            renderOrUpdate("#ch_priority", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/by-category",
                p
            );
            ({ labels, data } = toDataset(rows, "label", "total"));
            renderOrUpdate("#ch_category", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/by-type",
                p
            );
            ({ labels, data } = toDataset(rows, "label", "total"));
            renderOrUpdate("#ch_type", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/created-daily",
                p
            );
            ({ labels, data } = toDataset(rows, "dia", "total"));
            renderOrUpdate("#ch_created", {
                type: "line",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Criados",
                            data,
                            tension: 0.2,
                            borderColor: palette(1)[0],
                        },
                    ],
                },
                options: { responsive: true },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/resolved-daily",
                p
            );
            ({ labels, data } = toDataset(rows, "dia", "total"));
            renderOrUpdate("#ch_resolved", {
                type: "line",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Resolvidos",
                            data,
                            tension: 0.2,
                            borderColor: palette(2)[1],
                        },
                    ],
                },
                options: { responsive: true },
            });

            rows = await jgetWithParams("/admin/reports/api/tickets/aging", p);
            ({ labels, data } = toDataset(rows, "label", "total"));
            renderOrUpdate("#ch_aging", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Backlog",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/sla-monthly",
                p
            );
            ({ labels, data } = toDataset(rows, "mes", "sla"));
            renderOrUpdate("#ch_sla", {
                type: "line",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "SLA %",
                            data,
                            tension: 0.2,
                            borderColor: palette(3)[2],
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: { y: { suggestedMin: 0, suggestedMax: 100 } },
                },
            });
        }

        // NOVOS RANKINGS & SLA
        {
            let rows = await jgetWithParams(
                "/admin/reports/api/tickets/top-types",
                p
            );
            let { labels, data } = rankTop(rows, "label", "total", 10);
            renderOrUpdate("#ch_top_types", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    indexAxis: "y",
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/top-categories",
                p
            );
            ({ labels, data } = rankTop(rows, "label", "total", 10));
            renderOrUpdate("#ch_top_categories_rank", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    indexAxis: "y",
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/top-technicians",
                p
            );
            ({ labels, data } = rankTop(rows, "tecnico", "total", 10));
            renderOrUpdate("#ch_top_techs", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    indexAxis: "y",
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } },
                },
            });

            const sla = await jgetWithParams(
                "/admin/reports/api/tickets/on-time-overdue",
                p
            );
            const pieLabels = ["Dentro do prazo", "Vencidos"];
            const pieData = [
                Number(sla?.on_time || 0),
                Number(sla?.overdue || 0),
            ];
            renderOrUpdate("#ch_sla_pie", {
                type: "doughnut",
                data: {
                    labels: pieLabels,
                    datasets: [{ data: pieData, backgroundColor: palette(2) }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: "bottom" } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/tickets/top-priorities",
                p
            );
            ({ labels, data } = rankTop(rows, "label", "total", 5));
            renderOrUpdate("#ch_top_priorities", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                },
            });

            const idle = await jgetWithParams(
                "/admin/reports/api/technicians/idle",
                p
            );

            const ul = qs("#idle_list");
            if (ul) {
                ul.innerHTML =
                    idle && idle.length
                        ? idle
                              .map(
                                  (i) =>
                                      `<li>• ${i.tecnico} (${
                                          i.diasSemAtividade ?? "N/A"
                                      } dias sem atividade)</li>`
                              )
                              .join("")
                        : '<li class="text-gray-500">Nenhum técnico ocioso no período.</li>';
            }
        }

        // ==== INÍCIO DO BLOCO COMENTADO ====
        // Este bloco estava a causar os erros 404, pois as rotas de API não existem.
        /*
        // GRÁFICOS DE LOGS DO SISTEMA
        {
            let rows = await jgetWithParams(
                "/admin/reports/api/logs/top-actions",
                p
            );
            let { labels, data } = rankTop(rows, "action", "total", 10);
            renderOrUpdate("#lg_actions", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    indexAxis: "y",
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } },
                },
            });

            rows = await jgetWithParams("/admin/reports/api/logs/by-day", p);
            ({ labels, data } = toDataset(rows, "dia", "total"));
            renderOrUpdate("#lg_byday", {
                type: "line",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Logs",
                            data,
                            tension: 0.2,
                            borderColor: palette(5)[4],
                        },
                    ],
                },
                options: { responsive: true },
            });

            rows = await jgetWithParams("/admin/reports/api/logs/top-users", p);
            ({ labels, data } = rankTop(rows, "usuario", "total", 10));
            renderOrUpdate("#lg_users", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    indexAxis: "y",
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } },
                },
            });

            rows = await jgetWithParams(
                "/admin/reports/api/logs/top-routes",
                p
            );
            ({ labels, data } = rankTop(rows, "route", "total", 10));
            renderOrUpdate("#lg_routes", {
                type: "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: "Qtd",
                            data,
                            backgroundColor: palette(labels.length),
                        },
                    ],
                },
                options: {
                    responsive: true,
                    indexAxis: "y",
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } },
                },
            });

            rows = await jgetWithParams("/admin/reports/api/logs/methods", p);
            ({ labels, data } = toDataset(rows, "label", "total"));
            renderOrUpdate("#lg_methods", {
                type: "pie",
                data: {
                    labels,
                    datasets: [
                        { data, backgroundColor: palette(labels.length) },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: "bottom" } },
                },
            });
        }
        */
        // ==== FIM DO BLOCO COMENTADO ====

        // Atualizar links de export com os filtros correntes
        applyParamsToLink(
            document.getElementById("btnExportTickets"),
            "/admin/reports/export/tickets.csv",
            p
        );
        applyParamsToLink(
            document.getElementById("btnExportLogs"),
            "/admin/reports/export/logs.csv",
            p
        );
    } catch (error) {
        console.error("Erro ao carregar dados:", error);
        // A LINHA DO ALERT FOI COMENTADA ABAIXO
        // alert(
        //     "Erro ao carregar os relatórios. Verifique o console para mais detalhes."
        // );
    } finally {
        // Reabilita o botão
        if (btnApply) {
            btnApply.removeAttribute("disabled");
            btnApply.textContent = "Aplicar";
        }
    }
}

window.addEventListener("DOMContentLoaded", () => {
    // ADICIONE ESTA LINHA
    qs("#btnExportPDF")?.addEventListener("click", generatePdf);

    qs("#btnApply")?.addEventListener("click", loadAll);
    qs("#btnClear")?.addEventListener("click", () => {
        [
            "#f_status",
            "#f_priority",
            "#f_category",
            "#f_type",
            "#f_from",
            "#f_to",
            "#f_period",
        ].forEach((id) => {
            if (qs(id)) qs(id).value = "";
        });
        loadAll();
    });

    qs("#f_period")?.addEventListener("change", () => {
        const period = qs("#f_period")?.value || "";
        if (!period) return;
        const fromEl = qs("#f_from"),
            toEl = qs("#f_to");
        if ((fromEl && !fromEl.value) || (toEl && !toEl.value)) {
            const r = rangeFromPeriod(period);
            if (fromEl && !fromEl.value && r.date_from)
                fromEl.value = r.date_from;
            if (toEl && !toEl.value && r.date_to) toEl.value = r.date_to;
        }
    });

    loadAll();
});
