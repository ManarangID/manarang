<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))->addColumn('action', function(){
            $aksi = '<a href="" class="btn btn-info btn-sm"> edit</a>'; return $aksi;
        })->setRowId('id');
    }
 
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }
 
    public function html(): HtmlBuilder
    {
        // return $this->builder()
        //             ->setTableId('users-table')
        //             ->selectStyleSingle()
        //             ->columns($this->getColumns())
        //             ->minifiedAjax()
        //             ->orderBy(1)
        //             ->parameters([
        //                 'dom'          => 'Bfrtip',
        //                 'buttons'      => ["copy", "csv", "excel", "pdf", "print", "colvis"],
        //             ]);
        
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle();
    }
 
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center')
        ];
    }
 
    protected function filename(): string
    {
        return 'Users_'.date('YmdHis');
    }
}
