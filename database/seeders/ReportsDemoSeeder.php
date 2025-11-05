<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Category;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\Log;

// Opcional (auto-detecção)
use Spatie\Permission\Models\Role as SpatieRole;

class ReportsDemoSeeder extends Seeder
{
    public function run(): void
    {
        /* ============================
         * Limpeza leve de dados DEMO
         * ============================ */
        if (app()->environment('local')) {
            if (Schema::hasTable('logs')) {
                Log::whereIn('user_id', function ($q) {
                    $q->select('id')->from('users')->where('email', 'like', 'demo+%@example.com');
                })->delete();
            }
            if (Schema::hasTable('tickets')) {
                Ticket::whereIn('usuario_id', function ($q) {
                    $q->select('id')->from('users')->where('email', 'like', 'demo+%@example.com');
                })->orWhereIn('tecnico_id', function ($q) {
                    $q->select('id')->from('users')->where('email', 'like', 'demo+%@example.com');
                })->delete();
            }
            if (Schema::hasTable('users')) {
                User::where('email', 'like', 'demo+%@example.com')->delete();
            }
        }

        /* ============================
         * Detecta modo Role: Spatie ou Custom
         * ============================ */
        $rolesTableExists  = Schema::hasTable('roles');
        $rolesHasGuard     = $rolesTableExists && Schema::hasColumn('roles', 'guard_name');
        $usersHasRoleId    = Schema::hasColumn('users', 'role_id');

        $useSpatie = class_exists(SpatieRole::class) && $rolesHasGuard;

        // Helpers pra obter/crear roles em cada cenário
        $getRoleIdCustom = function (string $name) {
            $row = DB::table('roles')->where('name', $name)->first();
            if (!$row) {
                $id = DB::table('roles')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
                return $id;
            }
            return $row->id;
        };
        $getSpatieRole = function (string $name) {
            return SpatieRole::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        };

        // Garante roles
        $roleUsuarioId = null;
        $roleTecnicoId = null;
        $roleUsuario = null;
        $roleTecnico = null;
        if ($useSpatie) {
            $roleUsuario = $getSpatieRole('usuario');
            $roleTecnico = $getSpatieRole('tecnico');
        } elseif ($rolesTableExists) {
            $roleUsuarioId = $getRoleIdCustom('usuario');
            $roleTecnicoId = $getRoleIdCustom('tecnico');
        }

        /* ============================
         * Usuários (10) e Técnicos (8)
         * ============================ */
        $users = collect();
        for ($i = 1; $i <= 10; $i++) {
            $payload = [
                'name' => "Usuário {$i}",
                'email' => "demo+user{$i}@example.com",
                'password' => Hash::make('secret123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
            if (!$useSpatie && $usersHasRoleId && $roleUsuarioId) {
                $payload['role_id'] = $roleUsuarioId;
            }
            $u = User::create($payload);
            if ($useSpatie && $roleUsuario) $u->assignRole($roleUsuario);
            $users->push($u);
        }

        $techs = collect();
        for ($i = 1; $i <= 8; $i++) {
            $payload = [
                'name' => "Técnico {$i}",
                'email' => "demo+tech{$i}@example.com",
                'password' => Hash::make('secret123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
            if (!$useSpatie && $usersHasRoleId && $roleTecnicoId) {
                $payload['role_id'] = $roleTecnicoId;
            }
            $t = User::create($payload);
            if ($useSpatie && $roleTecnico) $t->assignRole($roleTecnico);
            $techs->push($t);
        }

        /* ============================
         * Categorias & Tipos
         *  - só cria se não houver
         * ============================ */
        if (Category::count() === 0) {
            foreach (
                [
                    'Infraestrutura',
                    'Sistemas',
                    'Redes',
                    'Suporte',
                    'Segurança',
                    'Banco de Dados',
                    'Telefonia',
                    'DevOps'
                ] as $cn
            ) {
                Category::create(['nome' => $cn]);
            }
        }
        $categories = Category::orderBy('id')->get();

        // Tipos com SLA base (usado p/ due_at). Só cria se não existir nenhum.
        $typesConfig = [
            'Infraestrutura' => [
                ['nome' => 'Troca de Equipamento', 'sla_h' => 72],
                ['nome' => 'Falha de Hardware', 'sla_h' => 48],
                ['nome' => 'Instalação de SO', 'sla_h' => 24],
            ],
            'Sistemas' => [
                ['nome' => 'Bug em Sistema', 'sla_h' => 24],
                ['nome' => 'Nova Funcionalidade', 'sla_h' => 168],
                ['nome' => 'Acesso/Permissões', 'sla_h' => 12],
            ],
            'Redes' => [
                ['nome' => 'Queda de Link', 'sla_h' => 8],
                ['nome' => 'Latência/Packet Loss', 'sla_h' => 24],
                ['nome' => 'Wi-Fi Cobertura', 'sla_h' => 48],
            ],
            'Suporte' => [
                ['nome' => 'Instalação de Software', 'sla_h' => 24],
                ['nome' => 'Configuração Impressora', 'sla_h' => 24],
                ['nome' => 'Backup/Restore', 'sla_h' => 36],
            ],
            'Segurança' => [
                ['nome' => 'Antivírus/Threat', 'sla_h' => 12],
                ['nome' => 'Política de Acesso', 'sla_h' => 72],
                ['nome' => 'Conformidade', 'sla_h' => 96],
            ],
            'Banco de Dados' => [
                ['nome' => 'Otimização de Query', 'sla_h' => 72],
                ['nome' => 'Backup/Restore BD', 'sla_h' => 24],
            ],
            'Telefonia' => [
                ['nome' => 'Ramal/URA', 'sla_h' => 48],
                ['nome' => 'PABX', 'sla_h' => 72],
            ],
            'DevOps' => [
                ['nome' => 'Pipeline CI/CD', 'sla_h' => 24],
                ['nome' => 'Deploy Release', 'sla_h' => 12],
                ['nome' => 'Infra como Código', 'sla_h' => 96],
            ],
        ];

        if (Type::count() === 0) {
            foreach ($typesConfig as $catName => $items) {
                foreach ($items as $t) {
                    Type::create([
                        'nome' => $t['nome'],
                        // se seu Type tiver category_id, descomente:
                        // 'category_id' => optional($categories->firstWhere('nome',$catName))->id,
                    ]);
                }
            }
        }

        // Mapa SLA base (por nome) p/ cálculo do due_at:
        $typeSla = [];
        foreach ($typesConfig as $cat => $items) {
            foreach ($items as $t) $typeSla[$t['nome']] = $t['sla_h'];
        }

        $types = Type::all();
        if ($types->isEmpty()) {
            // fallback mínimo
            $types = collect([Type::create(['nome' => 'Geral'])]);
            $typeSla['Geral'] = 48;
        }

        /* ============================
         * Tickets & Logs
         * ============================ */
        $statuses    = ['aberto', 'andamento', 'pendente', 'resolvido'];
        $statusW     = [0.15, 0.20, 0.10, 0.55];
        $prioridades = ['baixa', 'media', 'alta', 'muito alta'];
        $prioW       = [0.30, 0.35, 0.25, 0.10];

        $routes  = [
            '/admin/tickets',
            '/admin/tickets/create',
            '/admin/tickets/{id}',
            '/admin/reports',
            '/admin/reports/export/tickets.csv',
            '/profile',
            '/login',
            '/logout'
        ];
        $methods = ['GET', 'POST', 'PUT', 'DELETE'];
        $actions = ['login', 'logout', 'view_ticket', 'create_ticket', 'update_ticket', 'export_csv', 'dashboard_view'];

        $ticketsToCreate = 2500;
        $now = Carbon::now();

        $pickWeighted = function (array $weights) {
            $sum = array_sum($weights);
            $r = mt_rand() / mt_getrandmax() * $sum;
            $acc = 0;
            foreach ($weights as $i => $w) {
                $acc += $w;
                if ($r <= $acc) return $i;
            }
            return count($weights) - 1;
        };

        // Dois técnicos "ociosos" no período recente
        $idleTechs   = $techs->take(2)->pluck('id')->all();
        $activeTechs = $techs->skip(2)->pluck('id')->values()->all();

        for ($i = 1; $i <= $ticketsToCreate; $i++) {
            $created  = (clone $now)->subDays(rand(0, 365));
            $category = $categories->random();
            $type     = $types->random();

            $status = $statuses[$pickWeighted($statusW)];
            $prior  = $prioridades[$pickWeighted($prioW)];

            $tecnicoId = null;
            if (rand(1, 100) <= 90) {
                $tId = $activeTechs ? $activeTechs[array_rand($activeTechs)] : null;
                if (!$tId && $techs->count()) $tId = $techs->random()->id;

                if ($idleTechs && rand(1, 100) <= 10) {
                    $tId = $idleTechs[array_rand($idleTechs)];
                    $created = (clone $now)->subDays(rand(120, 365));
                }
                $tecnicoId = $tId;
            }

            $usuarioId = $users->random()->id;

            $baseSla  = $typeSla[$type->nome] ?? 48;
            $slaHours = max(4, (int) round($baseSla * (0.5 + (rand(0, 100) / 100))));
            $dueAt    = (clone $created)->addHours($slaHours);

            $resolvedAt = null;
            if ($status === 'resolvido') {
                if (rand(1, 100) <= 70) {
                    $resolvedAt = (clone $dueAt)->subHours(rand(1, 24));
                } else {
                    $resolvedAt = (clone $dueAt)->addHours(rand(1, 72));
                }
                if ($resolvedAt->lt($created)) {
                    $resolvedAt = (clone $created)->addHours(rand(1, 12));
                }
            } else {
                if (!(rand(1, 100) <= 40 && $dueAt->lt($now))) {
                    if ($dueAt->lt($now)) {
                        $dueAt = (clone $now)->addHours(rand(2, 72));
                    }
                }
            }

            $t = Ticket::create([
                'titulo'      => "Ticket #{$i} - {$type->nome}",
                'descricao'   => "Descrição de exemplo para o ticket #{$i} (tipo: {$type->nome})",
                'status'      => $status,
                'prioridade'  => $prior,
                'category_id' => $category->id,
                'type_id'     => $type->id,
                'usuario_id'  => $usuarioId,
                'tecnico_id'  => $tecnicoId,
                'created_at'  => $created,
                'updated_at'  => $resolvedAt ?? $created,
                'due_at'      => $dueAt,
                'resolved_at' => $resolvedAt,
            ]);

            // Logs relacionados
            $logCount = rand(2, 5);
            for ($j = 0; $j < $logCount; $j++) {
                $actor = (rand(1, 100) <= 60) ? $usuarioId : ($tecnicoId ?? $users->random()->id);
                $when  = (clone $created)->addMinutes(rand(0, 60 * 72));
                if ($resolvedAt && $when->gt($resolvedAt)) {
                    $when = (clone $resolvedAt)->subMinutes(rand(0, 60));
                }

                Log::create([
                    'user_id'    => $actor,
                    'action'     => $actions[array_rand($actions)],
                    'route'      => str_replace('{id}', $t->id, $routes[array_rand($routes)]),
                    'method'     => $methods[array_rand($methods)],
                    'ip_address' => '192.168.' . rand(0, 3) . '.' . rand(10, 250),
                    'user_agent' => 'SeederDemo/1.0 (Laravel)',
                    'created_at' => $when,
                    'updated_at' => $when,
                ]);
            }
        }

        // Logs "globais"
        for ($k = 0; $k < 1500; $k++) {
            $u = $users->random();
            $when = (clone $now)->subDays(rand(0, 90))->subMinutes(rand(0, 1440));
            Log::create([
                'user_id'    => $u->id,
                'action'     => $actions[array_rand($actions)],
                'route'      => $routes[array_rand($routes)],
                'method'     => $methods[array_rand($methods)],
                'ip_address' => '10.0.' . rand(0, 3) . '.' . rand(10, 250),
                'user_agent' => 'SeederDemo/1.0 (Laravel)',
                'created_at' => $when,
                'updated_at' => $when,
            ]);
        }

        $this->command->info('ReportsDemoSeeder: dados criados com sucesso (compatível com Spatie OU roles custom).');
    }
}
