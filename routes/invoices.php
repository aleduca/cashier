<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoice.index');
Route::get('/invoice', [InvoiceController::class, 'create'])->name('invoice.create');
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('/user/invoice/{id}', [InvoiceController::class, 'invoice'])->name('invoice.pdf');
