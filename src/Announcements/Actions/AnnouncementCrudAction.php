<?php

namespace App\Announcements\Actions;

use App\Announcements\Database\AnnouncementTable;
use ClientX\Actions\CrudAction;
use ClientX\Renderer\RendererInterface;
use ClientX\Router;
use ClientX\Session\FlashService;
use ClientX\Upload;
use ClientX\Validator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnnouncementCrudAction extends CrudAction
{

    protected $fillable = ["title", "content", "published", "pinned", "thumbnail"];

    protected $routePrefix = "announcements.crud";

    protected $viewPath = "@announcements_admin/crud";

    protected $moduleName = "Announcements";
    /**
     * @var \ClientX\Upload
     */
    private Upload $upload;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        FlashService $flash,
        AnnouncementTable $table,
        string $driver
    )
    {
        $this->upload = new Upload('public/uploads/announcements', $driver, ['thumbnail' => [600, 300], 'large' => [1000, 500]]);
        parent::__construct($renderer, $table, $router, $flash);
        $this->table = $table;
    }

    public function getValidator(Request $request): Validator
    {
        return parent::getValidator($request)
            ->notEmpty('title', "content")
            ->extension("thumbnail", ['png', 'gif', 'jpeg', 'jpg']);
    }

    public function afterUpdate(Request $request, $item, ?int $id = null)
    {

        try {

            /** @var \GuzzleHttp\Psr7\UploadedFile $thumbnail */
            $thumbnail = $request->getUploadedFiles()['thumbnail'];
            $extension = pathinfo($thumbnail->getClientFilename())['extension'] ?? null;
            if (!empty($thumbnail->getClientMediaType())) {
                $item->setThumbnail($this->upload->upload($thumbnail, "news$id." . $extension, "news$id." . $extension));
                $this->table->update($id, ['thumbnail' => "news$id" . "_thumbnail." . $extension]);

            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }


    }

    protected function findItem(int $id)
    {
        return $this->table->findOriginal($id);
    }

}
