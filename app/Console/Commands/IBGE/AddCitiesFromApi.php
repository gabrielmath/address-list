<?php

namespace App\Console\Commands\IBGE;

use App\Integrations\IBGE\IBGEInstitute;
use App\Integrations\InstituteInterface;
use App\Models\City;
use Illuminate\Console\Command;

class AddCitiesFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cities:add-from-api
                            {idUfList?* : Array dos Estados que deseja cadastrar as cidades}
                            {--fresh : Limpar toda base de dados (inclusive endereços cadastrados)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all cities from external API and save on Database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('fresh')) {
            $this->clearDatabase();
        }

        $idUfList = (!empty($this->argument('idUfList')) ? $this->argument('idUfList') : [41]);
        $ibge = $this->getInstitute(new IBGEInstitute());

        $statesNotFound = [];

        foreach ($idUfList as $id) {
            $cities = $ibge->getCities($id);

            if (empty($cities)) {
                $statesNotFound[] = $id;
                continue;
            }

            foreach ($cities as $city) {
                $newCity = $this->insertCity($city);
                $this->line('---------------------');
                $this->info("{$newCity->name}/{$newCity->uf} => OK!");
                $this->line('---------------------');
            }

            $this->info('Cidades cadastradas com sucesso!');
        }

        if (!empty($statesNotFound)) {
            $this->errorMessageTable($ibge, $statesNotFound);
            return 1;
        }

        return 0;
    }

    /**
     * Get institute from interface
     *
     * @param InstituteInterface $institute
     * @return InstituteInterface
     */
    private function getInstitute(InstituteInterface $institute): InstituteInterface
    {
        return $institute;
    }

    /**
     * Insert City in Database if not exist
     *
     * @param array $city
     * @return object
     */
    private function insertCity(array $city): object
    {
        if (City::whereIbgeId($city['ibge_id'])->exists()) {
            return (object)$city;
        }

        $newCity = City::create($city);

        return $newCity;
    }

    /**
     * Clear all data in Database
     *
     * @return void
     */
    private function clearDatabase(): void
    {
        \Artisan::call('migrate:fresh', [
            '--force' => true,
        ]);
    }

    /**
     * Error message with information table
     *
     * @param InstituteInterface $institute
     * @return void
     */
    private function errorMessageTable(InstituteInterface $institute, array $statesNotFound): void
    {
        $statesList = implode(', ', array_unique($statesNotFound));

        $this->error("Os IDs {$statesList} estão incorretos! Veja abaixo os IDs existentes:");
        $this->table(
            ['ID', 'Sigla', 'Nome'],
            $institute->getStates()
        );
        $this->line('Clique no link e veja o ID do seu Estado em JSON: ' . route('api.v1.uf.index'));
    }
}
