<?php

namespace App\Announcements\Database;

use App\Announcements\Entity\Announcement;
use Carbon\Carbon;
use ClientX\Database\Table;

class AnnouncementTable extends Table
{

    protected $table = "announcements";
    protected $entity = Announcement::class;
    protected $order = "id DESC";
    public function findPublic()
    {
        return $this->makeQuery()
            ->where("published = 1")
            ->order("id DESC")
            ->order("pinned DESC");
    }

    public function find(int $id)
    {
        return $this->findPublic()
            ->where("id = :id")
            ->params(compact('id'))
            ->fetchOrFail();
    }

    public function findOriginal(int $id)
    {
        return parent::find($id);
    }

    public function findWithMonths(int $length = 9): array
    {
        $announcements = $this->findPublic();
        return [$announcements->fetchAll(), $this->format($length)];
    }


    public function findForDate(int $year, int $month, $length = 9)
    {
        $announcements = $this->findPublic()
            ->where("YEAR(created_at) = :year")
            ->where("MONTH(created_at) = :month")
            ->params(['month' => $month, 'year' => $year]);
        return [$announcements->fetchAll(), $this->format($length)];
    }

    private function format($length = 9)
    {
        $rawDates = $this->findPublic()
            ->group("GROUP BY date_format(created_at,'%b %Y')")
            ->order("created_at DESC")
            ->limit($length)
            ->fetchAll();
        $months = [];
        foreach ($rawDates as $date) {
            $dateTime = Carbon::createFromTimestamp($date->getCreatedAt()->getTimestamp());
            $months[] = $dateTime->startOfMonth();
        }
        return $months;
    }

    public function insert(array $params): int
    {
        $params['published'] = (int)array_key_exists('published', $params);

        $params['pinned'] = (int)array_key_exists('pinned', $params);

        return parent::insert($params);
    }

    public function update($condition, $params, $where = 'id'): bool
    {
        if (array_key_exists('title', $params)) {

            $params['published'] = (int)array_key_exists('published', $params);
            $params['pinned'] = (int)array_key_exists('pinned', $params);
        }
        return parent::update($condition, $params, $where);
    }

    public function fetchPinned()
    {
        return $this->findPublic()->where("pinned = 1");
    }
}
