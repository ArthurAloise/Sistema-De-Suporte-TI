<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Setor;
use App\Models\Category;
use App\Models\Type;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Log;
use App\Models\TicketHistory;

class SeedAllSeeder extends Seeder
{
    public function run(): void
    {
        Cache::flush();

        // -------- Limpeza básica (MySQL) --------
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (['logs', 'tickets', 'users', 'categories', 'types', 'setores', 'roles', 'permissions'] as $tbl) {
            if (DB::getSchemaBuilder()->hasTable($tbl)) {
                DB::table($tbl)->truncate();
            }
        }
        // tenta truncar pivôs comuns (ignore se não existir)
        foreach (['permission_role', 'role_permission', 'model_has_permissions', 'model_has_roles', 'role_has_permissions'] as $pivot) {
            try {
                DB::table($pivot)->truncate();
            } catch (\Throwable $e) {
                // ignora caso a tabela não exista
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // -------- Permissões & Perfis --------
        $permAdmin      = Permission::create(['name' => 'acessar_admin']);
        $permPermissoes = Permission::create(['name' => 'acessar_permissoes']);
        $permPerfis     = Permission::create(['name' => 'acessar_perfis']);

        $roleAdmin  = Role::create(['name' => 'Administrador']);
        $roleTec    = Role::create(['name' => 'Tecnico']);
        $roleUser   = Role::create(['name' => 'Colaborador']);

        // Relaciona permissões aos perfis
        $roleAdmin->permissions()->sync([$permAdmin->id, $permPermissoes->id, $permPerfis->id]);
        $roleTec->permissions()->sync([$permAdmin->id]); // técnico acessa admin

        // -------- Setores fixos --------
        $setoresNomes = ['TI', 'Atendimento', 'RH', 'Financeiro', 'Compras', 'Operações', 'Marketing', 'Jurídico'];
        $setores = collect($setoresNomes)->map(fn($n) => Setor::create([
            'nome' => $n,
            'sigla' => Str::upper(Str::slug($n, ''))
        ]));

        // -------- Usuários --------
        $admin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id'  => $roleAdmin->id,
            'setor_id' => $setores->random()->id,
        ]);

        $tecnicos = collect(range(1, 8))->map(function ($i) use ($roleTec, $setores) {
            return User::create([
                'name'     => "Tecnico {$i}",
                'email'    => "tecnico{$i}@example.com",
                'password' => Hash::make('password'),
                'role_id'  => $roleTec->id,
                'setor_id' => $setores->random()->id,
            ]);
        });

        $users = collect(range(1, 30))->map(function ($i) use ($roleUser, $setores) {
            return User::create([
                'name'     => "Usuario {$i}",
                'email'    => "usuario{$i}@example.com",
                'password' => Hash::make('password'),
                'role_id'  => $roleUser->id,
                'setor_id' => $setores->random()->id,
            ]);
        });

        // -------- Categorias & Tipos --------
        $categories = collect(['Hardware', 'Software', 'Rede', 'Segurança', 'Acesso', 'Backup', 'Impressão', 'E-mail', 'Sistema Interno'])
            ->map(fn($n) => Category::create(['nome' => $n]));

        $types = collect(['Incidente', 'Requisição', 'Mudança', 'Problema', 'Alerta'])
            ->map(fn($n) => Type::create(['nome' => $n]));

        // -------- Tickets (500+) com distribuição p/ gráficos --------
        $prioridades = ['baixa' => 72, 'media' => 48, 'alta' => 24, 'muito alta' => 4]; // SLA horas
        $statusPool  = ['aberto', 'andamento', 'pendente', 'resolvido', 'fechado'];

        $totalTickets = 520;
        $now = now();

        for ($i = 1; $i <= $totalTickets; $i++) {
            $status = $statusPool[array_rand($statusPool)];
            $prioKeys = array_keys($prioridades);
            $prioridade = $prioKeys[array_rand($prioKeys)];

            // janela nos últimos 120 dias
            $created = Carbon::now()->subDays(rand(0, 120))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $due     = (clone $created)->addHours($prioridades[$prioridade]);

            $resolvedAt = null;
            $descResol  = null;
            $pendencia  = null;

            if (in_array($status, ['resolvido', 'fechado'])) {
                // 70% hit SLA, 30% miss
                if (rand(1, 100) <= 70) {
                    $resolvedAt = Carbon::createFromTimestamp(rand($created->timestamp, $due->timestamp));
                } else {
                    $resolvedAt = (clone $due)->addHours(rand(1, 24 * 7));
                }
                $descResol = 'Procedimento padrão aplicado e validado com o usuário.';
            } elseif ($status === 'pendente') {
                $pendencia = 'Aguardando retorno do usuário/fornecedor.';
                // 50% vencidos, 50% a vencer
                if (rand(0, 1)) {
                    $due = Carbon::now()->subHours(rand(1, 72));
                } else {
                    $due = Carbon::now()->addHours(rand(1, 72));
                }
            } else {
                // aberto/andamento: mistura de vencidos e a vencer
                if (rand(1, 100) <= 45) {
                    $due = Carbon::now()->subHours(rand(1, 240)); // vencido
                } else {
                    $due = Carbon::now()->addHours(rand(1, 96));  // a vencer
                }
            }

            $ticket = new Ticket();
            $ticket->titulo      = 'Chamado ' . $i . ' - ' . Str::ucfirst(fake()->words(3, true));
            $ticket->descricao   = fake()->paragraph();
            $ticket->status      = $status;
            $ticket->prioridade  = $prioridade;
            $ticket->category_id = $categories->random()->id;
            $ticket->type_id     = $types->random()->id;
            $ticket->usuario_id  = $users->random()->id;
            $ticket->tecnico_id  = rand(1, 100) <= 80 ? $tecnicos->random()->id : null;
            $ticket->due_at      = $due;
            $ticket->resolved_at = $resolvedAt;
            $ticket->descricao_resolucao = $descResol;
            $ticket->pendencia   = $pendencia;
            $ticket->created_at  = $created;
            $ticket->updated_at  = Carbon::createFromTimestamp(rand($created->timestamp, $now->timestamp));
            $ticket->save();

            // Históricos (opcional)
            if (class_exists(TicketHistory::class)) {
                $histCount = rand(1, 3);
                for ($h = 0; $h < $histCount; $h++) {
                    TicketHistory::create([
                        'ticket_id' => $ticket->id,
                        'user_id'   => ($ticket->tecnico_id ?? $users->random()->id),
                        'tipo_acao' => fake()->randomElement(['criado', 'edicao_ticket', 'atribuicao_tecnico', 'marcar_pendencia', 'conclusao_chamado']),
                        'descricao' => fake()->sentence(8),
                        'created_at' => Carbon::createFromTimestamp(rand($created->timestamp, $now->timestamp)),
                    ]);
                }
            }
        }

        // Pacote “extremo” pra garantir cartões de SLA gritarem
        foreach (range(1, 20) as $k) {
            Ticket::create([
                'titulo'      => 'Urgente - Link crítico ' . $k,
                'descricao'   => 'Queda total de serviço. Ação imediata necessária.',
                'status'      => 'andamento',
                'prioridade'  => 'muito alta',
                'category_id' => $categories->where('nome', 'Rede')->first()->id,
                'type_id'     => $types->where('nome', 'Incidente')->first()->id,
                'usuario_id'  => $users->random()->id,
                'tecnico_id'  => $tecnicos->random()->id,
                'due_at'      => now()->subHours(rand(2, 48)), // vencido
                'created_at'  => now()->subDays(rand(2, 10)),
                'updated_at'  => now(),
            ]);
        }

        // -------- Logs (5500+) para gráficos de logs --------
        $allUsers = $users->concat($tecnicos)->push($admin);
        $actions  = ['login', 'logout', 'ticket_create', 'ticket_update', 'view', 'report_view', 'user_update', 'permission_update'];
        $routes   = ['/tickets', '/tickets/create', '/tickets/{id}', '/admin', '/admin/reports', '/admin/users', '/admin/logs', '/admin/categories', '/admin/types', '/user/profile'];
        $methods  = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

        // checa se existe a coluna user_agent para evitar erro 1364
        $hasUA = Schema::hasColumn('logs', 'user_agent');

        $bulk = [];
        $totalLogs = 5500;
        for ($i = 0; $i < $totalLogs; $i++) {
            $dt = Carbon::now()->subDays(rand(0, 120))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $row = [
                'user_id'    => $allUsers->random()->id,
                'action'     => $actions[array_rand($actions)],
                'route'      => $routes[array_rand($routes)],
                'method'     => $methods[array_rand($methods)],
                'ip_address' => fake()->ipv4(),
                'created_at' => $dt,
                'updated_at' => $dt,
            ];

            if ($hasUA) {
                $row['user_agent'] = fake()->userAgent() ?: 'Mozilla/5.0';
            }

            $bulk[] = $row;

            if (count($bulk) >= 1000) {
                DB::table('logs')->insert($bulk); // usa DB para não depender de fillable
                $bulk = [];
            }
        }
        if ($bulk) {
            DB::table('logs')->insert($bulk);
        }

        // Força muitos "report_view" recentes pra ficar no topo no gráfico
        foreach (range(1, 300) as $i) {
            $row = [
                'user_id'    => $admin->id,
                'action'     => 'report_view',
                'route'      => '/admin/reports',
                'method'     => 'GET',
                'ip_address' => fake()->ipv4(),
                'created_at' => Carbon::now()->subDays(rand(0, 60))->subMinutes(rand(0, 59)),
                'updated_at' => now(),
            ];
            if ($hasUA) {
                $row['user_agent'] = fake()->userAgent() ?: 'Mozilla/5.0';
            }
            DB::table('logs')->insert($row);
        }

        $this->command?->info('SeedAllSeeder concluído. Admin: admin@example.com / password');
    }
}
