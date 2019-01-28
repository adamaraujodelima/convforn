<?php

namespace App;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use Illuminate\Support\Facades\DB;

class CompanyRepository extends BaseRepository implements CacheableInterface {

    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return "App\\Company";
    }

    public function getTotalMonthPayment($user)
    {
        $totalPayments = DB::table('company')
            ->groupBy('manufacturer.user_id')
            ->where('manufacturer.user_id', $user->id)
            ->leftJoin('manufacturer', 'company.user_id', '=', 'manufacturer.user_id')
            ->select('manufacturer.month_payment')->sum('manufacturer.month_payment');

        return $totalPayments;
    }
}