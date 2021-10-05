<?php
namespace App\Announcements\Actions;

use App\Announcements\Database\AnnouncementTable;
use ClientX\Actions\Action;
use ClientX\Database\NoRecordException;
use ClientX\Renderer\RendererInterface;
use ClientX\Router;
use Psr\Http\Message\ServerRequestInterface;

class AnnouncementAction extends Action
{

    /**
     * @var AnnouncementTable
     */
    private $table;

    public function __construct(AnnouncementTable $table, RendererInterface $renderer, Router $router)
    {
        $this->table = $table;
        $this->renderer = $renderer;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        if (is_null($request->getAttribute('year')) === false) {
            return $this->view($request);
        } elseif (is_null($id)) {
            return $this->index($request);
        }
        return $this->show($request, (int)$id);
    }

    private function view(ServerRequestInterface $request)
    {
        $year = $request->getAttribute('year');
        $month = $request->getAttribute('month');
        [$announcements, $months] = $this->table->findForDate($year, $month);
        $current = join("-", [$year, $month]);
        return $this->render("@announcements/index", compact("announcements", "months", "current"));
    }

    private function index(ServerRequestInterface $request)
    {
        [$announcements, $months] = $this->table->findWithMonths();
        return $this->render("@announcements/index", compact("announcements", "months"));
    }

    private function show(ServerRequestInterface $request, int $id)
    {
        try {
            $announcement = $this->table->find($id);
            return $this->render("@announcements/show", compact("announcement"));
        } catch (NoRecordException $e) {
            return $this->redirectToRoute("announcements.index");
        }
    }
}
