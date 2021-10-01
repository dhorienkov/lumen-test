<?php
// Copyright © LoveCrafts Collective Ltd - All Rights Reserved

namespace App\Console\Commands;

use App\Entities\Product;
use Illuminate\Console\Command;
use League\Csv\Reader;

/**
 * Class ProductUploadCommand
 * @package App\Console\Commands
 *
 * Command to Upload Products from file
 */
class ProductUploadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:upload {--filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload products';

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
        $filePath = storage_path() . '/files/primex-products-test.csv';

        if ($this->hasArgument('filepath')) {
            $filePath = $this->argument('filepath');
        }
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        $count = count($csv); // ResultSet implements Countable interface

        $this->info('Upload started');
        foreach ($csv->getRecords() as $record) {
            $product = new Product($record['code'], $record['name'], $record['description']);
            try {
                //Doctrine will automatically understand should it be inserted or updated
                \EntityManager::persist($product);
            } catch (\Throwable $exception) {
                throw new \RuntimeException($exception->getMessage());
            }
        }

        \EntityManager::flush();

        $this->info("Upload finished. Processed {$count} items");
    }
}
