<?php
// Copyright © LoveCrafts Collective Ltd - All Rights Reserved

namespace App\Console\Commands;

use Doctrine\DBAL\Connection;
use Illuminate\Console\Command;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class StockUploadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:upload {--filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload stock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        //load the CSV document from a file path
        $filePath = storage_path() . '/files/primex-stock-test.csv';

        if ($this->hasArgument('filepath')) {
            $filePath = $this->argument('filepath');
        }
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        $count = count($csv); // ResultSet implements Countable interface

        $placeholders = [];
        $values = [];
        $types = [];
        $sql = <<<SQL
 INSERT INTO `stock` (`product_id`, `on_hand`, `taken`, `production_date`, `created_at`, `updated_at`)  VALUES 
 SQL;

        foreach ($csv->getRecords() as $record) {
            var_dump($record);
            $product = \EntityManager::getRepository('App\Entities\Product')
                ->findOneBy(['code' => $record['product_code']]);
            if(!$product) {
                continue;
            }
            $placeholders[] = '(?)';
            $values[] = [
                $product->getId(),
                $record['on_hand'],
                0,
                \DateTime::createFromFormat('d/m/Y', $record['production_date'])
                    ->format('Y-m-d h:m:s'),
                (new \DateTime('now'))->format('Y-m-d h:m:s'),
                (new \DateTime('now'))->format('Y-m-d h:m:s'),
            ];
            $types[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
        }

        // as we have associations better to use DBAL
        DB::connection()->getDoctrineConnection()->executeStatement(
            $sql . implode(', ', $placeholders),
            $values,
            $types
        );

        $this->info("Upload finished. Processed {$count} items");
    }
}
