<?php

namespace App\Console\Commands;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ArticlesRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "articles:restore {file=articles : base name of the output file. This will have '.tar.gz' added} {--force : Update from file regardless of update date}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore articles from tar archive';

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
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $out = $this->getOutput();
        $file = $this->argument('file') . '.tar.gz';
        $force = $this->option('force');
        try {
            if (!file_exists($file))
                throw new \Exception("File: '{$file}' does not exist");

            $archive = new \PharData($file);

            foreach (new \RecursiveIteratorIterator($archive) as $file) {
                if( Str::endswith($file->getFileName(), '.json')) {
                    $out->title('Processing: ' . $file->getFileName());

                    $json = json_decode($file->getContent(), true);

                    if (empty($json['slug'])||empty($json['html'])||empty($json['title'])||empty($json['category'])||empty($json['created_at'])||empty($json['updated_at'])) {
                        $out->error('Missing required fields: skipping');
                        continue;
                    }

                    $article = Article::slug($json['slug'])->first();
                    if ($article) {
                        $update = false;
                        $out->text('Article already exists');
                        $updated = new Carbon($json['updated_at']);
                        $out->text('File version updated: ' . $updated->toIso8601String());
                        $out->text('DB   version updated: ' . $article->updated_at->toIso8601String());
                        if ($updated > $article->updated_at) {
                            $out->note('File version is newer: updating.');
                            $update = true;
                        }
                        if ($force) {
                            $out->note('Forced update from file.');
                            $update = true;
                        }
                        if ($update) {
                            $article->title = $json['title'];
                            $article->category = $json['category'];
                            $article->html = $json['html'];
                            $article->published = $json['published'];
                            $article->created_at = $json['created_at'];
                            $article->updated_at = $json['updated_at'];
                            $article->save();
                        }
                    }
                    else
                    {
                        $out->text('New article.');
                        $article = new Article([
                            'slug' => $json['slug'],
                            'title' => $json['title'],
                            'category' => $json['category'],
                            'html' => $json['html'],
                            'published' => $json['published'],
                            'created_at' => $json['created_at'],
                            'updated_at' => $json['updated_at'],
                        ]);
                        $article->save();
                    }
                }
            }
        }
        catch(\Exception $e) {
            $out->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
