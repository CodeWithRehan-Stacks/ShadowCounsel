<?php

namespace Database\Seeders;

use App\Models\AgentExecutionLog;
use App\Models\AgentSession;
use App\Models\ClientPortfolio;
use App\Models\FinancialCase;
use App\Models\FinancialRegulation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── 1. Core Admin Users ───────────────────────────────────────────────
        $superAdmin = User::factory()->superAdmin()->create([
            'name'         => 'Shadow Admin',
            'email'        => 'admin@shadowcounsel.com',
            'job_title'    => 'Platform Administrator',
            'organization' => 'ShadowCounsel HQ',
            'timezone'     => 'UTC',
        ]);

        User::factory()->create([
            'name'         => 'Compliance Officer',
            'email'        => 'compliance@shadowcounsel.com',
            'role'         => 'admin',
            'job_title'    => 'Chief Compliance Officer',
            'organization' => 'ShadowCounsel HQ',
        ]);

        // Regular users
        User::factory(20)->create();

        // ── 2. Financial Regulations ──────────────────────────────────────────
        $regulations = collect([
            ['source_agency' => 'SEC',   'regulation_code' => 'SEC-17A-4',   'title' => 'Electronic Records Retention',         'effective_date' => '2023-01-15'],
            ['source_agency' => 'FINRA', 'regulation_code' => 'FINRA-4511',  'title' => 'FINRA Books and Records Requirements',  'effective_date' => '2022-11-01'],
            ['source_agency' => 'IRS',   'regulation_code' => 'IRC-482',     'title' => 'Transfer Pricing Regulations',          'effective_date' => '2023-06-01'],
            ['source_agency' => 'FDIC',  'regulation_code' => 'FDIC-12CFR',  'title' => 'Bank Deposit Insurance Standards',      'effective_date' => '2024-01-01'],
            ['source_agency' => 'CFPB',  'regulation_code' => 'CFPB-1026',   'title' => 'Truth in Lending Act (Regulation Z)',   'effective_date' => '2023-09-15'],
            ['source_agency' => 'OCC',   'regulation_code' => 'OCC-12CFR-30','title' => 'Bank Safety and Soundness Standards',   'effective_date' => '2024-03-01'],
        ])->map(fn ($reg) => FinancialRegulation::create([
            'source_agency'   => $reg['source_agency'],
            'regulation_code' => $reg['regulation_code'],
            'title'           => $reg['title'],
            'description'     => fake()->paragraph(4),
            'effective_date'  => $reg['effective_date'],
            'metadata'        => [
                'document_url'   => 'https://regulations.gov/' . Str::slug($reg['regulation_code']),
                'embedding_ref'  => Str::uuid(),
                'version'        => '1.0',
                'tags'           => fake()->words(3),
            ],
        ]));

        // ── 3. Client Portfolios ──────────────────────────────────────────────
        $portfolios = collect([
            ['name' => 'Apex Financial Corp',   'jurisdiction' => 'US-NY', 'fs' => '01-01', 'fe' => '12-31'],
            ['name' => 'Meridian Capital LLC',  'jurisdiction' => 'US-DE', 'fs' => '04-01', 'fe' => '03-31'],
            ['name' => 'Orion Tax Partners',    'jurisdiction' => 'UK-EN', 'fs' => '04-06', 'fe' => '04-05'],
            ['name' => 'Vanguard Legal Group',  'jurisdiction' => 'US-CA', 'fs' => '01-01', 'fe' => '12-31'],
            ['name' => 'Nexus Compliance Ltd',  'jurisdiction' => 'SG',    'fs' => '01-01', 'fe' => '12-31'],
        ])->map(fn ($p) => ClientPortfolio::create([
            'name'         => $p['name'],
            'jurisdiction' => $p['jurisdiction'],
            'fiscal_year_start' => now()->format('Y') . '-' . $p['fs'],
            'fiscal_year_end'   => now()->format('Y') . '-' . $p['fe'],
            'corporate_entity_metadata' => [
                'entity_type'    => fake()->randomElement(['LLC', 'Corp', 'LLP', 'Inc']),
                'registration_no'=> strtoupper(Str::random(8)),
                'incorporation'  => fake()->country(),
                'sic_code'       => (string) fake()->numberBetween(1000, 9999),
            ],
        ]));

        // ── 4. Financial Cases ────────────────────────────────────────────────
        $statuses = ['OPEN', 'IN_REVIEW', 'PENDING_CLIENT', 'RESOLVED', 'CLOSED'];

        $cases = $portfolios->flatMap(fn ($portfolio) =>
            collect(range(1, rand(2, 4)))->map(fn () =>
                FinancialCase::create([
                    'client_portfolio_id'       => $portfolio->id,
                    'title'                     => fake()->sentence(6),
                    'description'               => fake()->paragraph(3),
                    'status'                    => fake()->randomElement($statuses),
                    'document_verification_hash'=> hash('sha256', Str::uuid()),
                    'high_precision_data'       => [
                        'amount_usd'      => fake()->randomFloat(4, 10000, 5000000),
                        'tax_rate'        => fake()->randomFloat(4, 0.05, 0.40),
                        'currency'        => fake()->currencyCode(),
                        'fiscal_quarter'  => 'Q' . fake()->numberBetween(1, 4),
                        'audit_year'      => fake()->year(),
                    ],
                ])
            )
        );

        // ── 5. Agent Sessions & Execution Logs ───────────────────────────────
        $agentNames = ['FinancialAdvisor', 'LegalCompliance', 'TaxParser', 'RegulatoryAuditor', 'RiskAnalyzer'];

        $cases->each(function ($case) use ($agentNames) {
            $sessionCount = rand(1, 3);

            collect(range(1, $sessionCount))->each(function () use ($case, $agentNames) {
                $totalTokens = fake()->numberBetween(1000, 128000);

                $session = AgentSession::create([
                    'financial_case_id'    => $case->id,
                    'orchestrator_version' => 'Nemotron-3-Ultra',
                    'context_window_usage' => $totalTokens,
                    'total_tokens'         => $totalTokens,
                    'session_metadata'     => [
                        'model'       => 'nemotron-3-ultra-253b',
                        'temperature' => 0.2,
                        'stream'      => true,
                        'system'      => 'financial-compliance-v2',
                    ],
                ]);

                // 2-6 sub-agent execution steps per session
                collect(range(1, rand(2, 6)))->each(function ($step) use ($session, $agentNames) {
                    $inputTokens  = fake()->numberBetween(200, 4096);
                    $outputTokens = fake()->numberBetween(100, 2048);
                    $costPer1k    = 0.003;

                    AgentExecutionLog::create([
                        'agent_session_id'  => $session->id,
                        'step_name'         => fake()->randomElement($agentNames),
                        'input_tokens'      => $inputTokens,
                        'output_tokens'     => $outputTokens,
                        'execution_time_ms' => fake()->numberBetween(150, 8000),
                        'token_cost'        => round(($inputTokens + $outputTokens) / 1000 * $costPer1k, 6),
                        'tool_calls'        => [
                            [
                                'tool'      => fake()->randomElement(['sec_lookup', 'tax_calculator', 'finra_check', 'regulation_parser']),
                                'status'    => 'success',
                                'duration_ms' => fake()->numberBetween(50, 2000),
                                'result_ref'  => Str::uuid(),
                            ],
                        ],
                    ]);
                });
            });
        });

        $this->command->info('✅  ShadowCounsel database seeded successfully.');
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Users',                User::count()],
                ['Financial Regulations', FinancialRegulation::count()],
                ['Client Portfolios',     ClientPortfolio::count()],
                ['Financial Cases',       FinancialCase::count()],
                ['Agent Sessions',        AgentSession::count()],
                ['Agent Execution Logs',  AgentExecutionLog::count()],
            ]
        );
    }
}
