<?php

class ReportController extends Controller
{
    public function daily(): void
    {
        Auth::requirePermission('reports');

        $date = $this->input('date', date('Y-m-d'));

        $sales     = Report::dailySales($date);
        $purchases = Report::dailyPurchases($date);
        $profit    = Report::dailyProfit($date);
        $topItems  = Report::topProducts($date);
        $catSales  = Report::categorySales($date);
        $txns      = Report::transactionSummary($date);

        $this->view('reports.index', [
            'title'     => 'Daily Report',
            'date'      => $date,
            'sales'     => $sales,
            'purchases' => $purchases,
            'profit'    => $profit,
            'topItems'  => $topItems,
            'catSales'  => $catSales,
            'txns'      => $txns,
        ]);
    }
}
