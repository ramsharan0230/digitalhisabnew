<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by Thesee RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace'=>'Admin'],function(){
	Route::get('/','LoginController@login')->name('login');
	Route::post('postLogin','LoginController@postLogin')->name('postLogin');
	Route::get('password-reset', 'PasswordResetController@resetForm')->name('password-reset');
    Route::post('send-email-link', 'PasswordResetController@sendEmailLink')->name('sendEmailLink');
    Route::get('reset-password/{token}', 'PasswordResetController@passwordResetForm')->name('passwordResetForm');
    Route::post('update-password', 'PasswordResetController@updatePassword')->name('updatePassword');
});

Route::group(['namespace'=>'Admin','middleware'=>['auth'],'prefix'=>'admin'],function(){
	Route::post('sales-purchase-vat-by-year','DashboardController@salesAndPurchaseVatByYear')->name('salesAndPurchaseVatByYear');
	Route::resource('dashboard','DashboardController');
	Route::get('logout','LoginController@Logout')->name('logout');
	//contact
	Route::group(['prefix'=>'contact'],function(){
		Route::get('client-invoice-preview/{id}','ClientController@clientInvoicePreview')->name('clientInvoicePreview');
		Route::get('other-receipts/{id}','ClientController@otherReceipts')->name('otherReceipts');
		Route::post('search-monthly-client-business-detail','ClientController@searchMonthlyClientBusinessDetail')->name('searchMonthlyClientBusinessDetail');
		Route::post('export-client-transaction','ClientController@exportClientTransaction')->name('admin.contact.export-client-transaction');
		Route::post('custom-client-search','ClientController@clientCustomSearch')->name('customClientSearch');
		Route::resource('client','ClientController');
		Route::get('transaction-view/{id}','VendorController@transactionView')->name('vendorTransactionView');
		Route::post('search-monthly-vendor-business-detail','VendorController@searchMonthlyVendorBusinessDetail')->name('searchMonthlyVendorBusinessDetail');
		Route::post('export-vendor-transaction','VendorController@exportVendorTransaction')->name('exportVendorTransaction');
		Route::post('custom-vendor-search','VendorController@customVendorSearch')->name('customVendorSearch');
		Route::resource('vendor','VendorController');
	});
	
	Route::group(['middleware'=>['role']],function(){
		Route::resource('user','UserController');
		Route::group(['prefix'=>'report'],function(){
			Route::get('sales-report','ReportController@salesReport')->name('salesReport');
			Route::post('sales-report-pdf','ReportController@salesReportPdf')->name('salesReportPdf');
			
			Route::post('salesSearchByMonth','ReportController@salesSearchByMonth')->name('reportSalesSearchByMonth');
			Route::post('custom-sales-search','ReportController@customSalesSearch')->name('reportCustomSalesSearch');
			Route::get('report-invoice-view/{id}','ReportController@reportInvoiceView')->name('reportInvoiceView');
			Route::post('report-print-invoito-be-paidce','ReportController@reportPrintInvoice')->name('reportPrintInvoice');
			Route::get('receipt-list','ReportController@receiptList')->name('reportReceiptList');
			Route::post('receipt-search-month','ReportController@receiptSearchByMonth')->name('reportReceiptSearchByMonth');
			Route::post('custom-receipt-search','ReportController@customReceiptSearch')->name('customReceiptSearch');
			Route::get('purchase-report','ReportController@purchaseReport')->name('purchaseReport');
			Route::post('purchase-report-pdf','ReportController@purchaseReportPdf')->name('purchaseReportPdf');
			
			Route::post('custom-purchase-report','ReportController@customPurchaseReport')->name('reportCustomPurchaseReport');
			Route::post('report-purchase-view','ReportController@purchaseView')->name('reportPurchaseView');
			Route::post('monthly-purchase-report','ReportController@monthlyPurchaseReport')->name('reportMonthlyPurchaseReport');
			Route::get('day-book','ReportController@daybook')->name('reportDaybook');
			Route::post('daybook-pdf','ReportController@daybookPdf')->name('daybookPdf');

			Route::post('custom-daybook-search','ReportController@customDaybookSearch')->name('customDateDaybookSearch');
			Route::get('tds-to-be-collected','ReportController@tdsToBeCollected')->name('reportTdsToBeCollected');
			Route::get('tds-to-be-paid','ReportController@tdsToBePaid')->name('reportTdsToBePaid');
			Route::get('vat-collected','ReportController@vatCollected')->name('reportVatCollected');
			Route::get('vat-paid','ReportController@vatPaid')->name('reportVatPaid');
			Route::get('vendors','ReportController@vendorList')->name('reportVendorList');
			Route::get('clients','ReportController@clientList')->name('reportClientList');
			Route::get('payment','ReportController@paymentList')->name('reportPaymentList');
			Route::post('report-monthly-payment','ReportController@reportMonthlyPayment')->name('reportMonthlyPayment');
			Route::post('custom-payment-search','ReportController@customPaymentSearch')->name('customPaymentSearch');
			Route::get('invoice-list','ReportController@invoiceList')->name('reportinvoiceList');
			Route::post('custom-invoice-search','ReportController@customInvoiceSearch')->name('customInvoiceSearch');
			Route::post('report-monthly-vat-paid','ReportController@reportMonthlyVatPaid')->name('reportMonthlyVatPaid');

			Route::post('vatPaidExport','ReportController@reportMonthlyVatPaidPDF')->name('vatPaidExport');

			Route::post('monthly-tds-collected','ReportController@monthlyTdsToBeCollected')->name('reportMonthlyTdsCollected');
			Route::post('custom-tds-collected','ReportController@customTdsCollected')->name('reportCustomTdsCollected');
			Route::post('custom-tds-paid','ReportController@customTdsPaid')->name('reportCustomTdsPaid');
			Route::get('profit-and-loss','ReportController@profitAndLoss')->name('reportProfitAndLoss');
			Route::post('profit-and-loss-by-month','ReportController@profitAndLossByMonth')->name('reportProfitAndLossByMonth');
			Route::post('profit-and-loss-by-month-pdf','ReportController@profitAndLossByMonthPdf')->name('profit-and-loss-by-month-pdf');
			Route::post('custom-profit_and_loss','ReportController@customProfitAndLoss')->name('reportCustomProfitAndLoss');
			Route::post('daybook-export','ReportController@dayBookExport')->name('dayBookExport');

			
			//new report 

			Route::post('annual-daybook-export','ReportController@annualBookExport')->name('admin.report.annualbook-export');
			Route::post('annualsales-export','ReportController@annualSalesExport')->name('admin.report.annualsales-export');
			
			Route::post('receipt-list-export','ReportController@receiptListExport')->name('admin.report.receiptListExport');
			Route::post('invoice-list-export','ReportController@invoiceListExport')->name('admin.report.invoiceListExport');
			Route::post('tds-to-be-collected-export','ReportController@tdsToBeCollectedExport')->name('admin.report.tdsToBeCollectedExport');
			Route::post('salesSearchByYear','ReportController@salesSearchByYear')->name('salesSearchByYear');

		});
		Route::group(['prefix'=>'invoice'],function(){
			Route::post('print-invoice','InvoiceController@printInvoice')->name('printInvoice');
			Route::post('saveClient','InvoiceController@saveClient')->name('saveClient');
			Route::post('find-client','InvoiceController@findClient')->name('findClient');
			Route::get('view-invoice/{id}','InvoiceController@previewInvoice')->name('previewInvoice');
			Route::post('send-invoice','InvoiceController@sendInvoice')->name('sendInvoice');
			Route::post('saveVatDetail/{id}','InvoiceController@saveVatDetail')->name('saveVatDetail');
			Route::get('mark-collected-vat/{id}','InvoiceController@markCollectedVat')->name('markCollectedVat');
			Route::get('save-sales-without-vat/{id}','InvoiceController@saveSalesWithoutVat')->name('saveSalesWithoutVat');
			Route::post('check-amount-to-be-collected','InvoiceController@checkAmountToBeCollcted')->name('checkAmountToBeCollcted');
			Route::post('pay-collected-amount','InvoiceController@payCollectedAmount')->name('payCollectedAmount');
			Route::post('pay-full-amount','InvoiceController@payFullAmount')->name('payFullAmount');
			Route::get('re-invoice/{id}','InvoiceController@reInvoice')->name('reInvoice');
			Route::post('saveFullAmount','InvoiceController@saveFullAmount')->name('saveFullAmount');
			Route::get('invoice-report','InvoiceController@report')->name('invoiceReport');
			Route::post('invoice-monthly-report','InvoiceController@invoiceMonthlyReport')->name('invoiceMonthlyReport');
			Route::post('custom-collected-vat-search','ReportController@customCollectedVatSearch')->name('customCollectedVatSearch');
			Route::post('reportInvoiceSearchByMonth','ReportController@reportInvoiceSearchByMonth')->name('reportInvoiceSearchByMonth');
			Route::post('monthly-vat-collected','ReportController@monthlyVatCollected')->name('reportMonthlyVatCollected');
			Route::post('monthly-vat-collected-pdf','ReportController@monthlyVatCollectedPDF')->name('reportMonthlyVatCollectedPdf');
			Route::post('custom-vat-paid-search','ReportController@customVatPaidSearch')->name('customVatPaidSearch');
			
		});
		
		Route::resource('invoice','InvoiceController');
		Route::get('invoice-filter','InvoiceController@invoiceFilter')->name('invoice-filter');
		Route::post('invoice-report-ym-pdf','InvoiceController@invoiceExportPdf')->name('invoice-report-ym-pdf');
		//vat
		Route::group(['prefix'=>'sales'],function(){
			Route::get('sales-vat','VatController@salesVat')->name('salesVat');
			Route::get('without-vat','SalesController@salesWithoutVat')->name('salesWithoutVat');
			Route::post('searchSalesWithoutVat','SalesController@searchSalesWithoutVat')->name('searchSalesWithoutVat');
			Route::post('salesSearchByMonth','SalesController@salesSearchByMonth')->name('salesSearchByMonth');
			Route::post('salesSearchBySalesWithoutVat','SalesController@salesSearchBySalesWithoutVat')->name('salesSearchBySalesWithoutVat');
			Route::get('to-be-collected','SalesController@toBeCollected')->name('toBeCollected');
			Route::post('toBeCollectedPdf','SalesController@toBeCollectedPdf')->name('toBeCollectedPdf');
			
			Route::post('sales-view','SalesController@salesView')->name('salesView');
			Route::get('invoice-view/{id}','SalesController@invoiceView')->name('invoiceView');


		});

		Route::resource('sales','SalesController');
		Route::post('sales-report', 'SalesController@salesReport')->name('sales-report');
		Route::post('sales-report-pdf', 'SalesController@salesReportPdf')->name('sales-report-pdf');

		Route::resource('payment-gateway','PaymentGatewayController');
		Route::post('searchByMonth','VatController@searchByMonth')->name('searchByMonth');
		Route::post('vat/export-vat','VatController@exportVat')->name('vatExport');

		//purchase
		Route::post('purchase/save-vendor','PurchaseController@saveVendor')->name('saveVendor');
		Route::get('purchase/to-be-paid','PurchaseController@toBePaid')->name('toBePaid');
		Route::get('to-be-paid-date-filter', 'PurchaseController@toBePaidDateFilter')->name('to-be-paid-date-filter');
		Route::post('to-be-paid-pdf', 'PurchaseController@toBePaidPdf')->name('to-be-paid-pdf');
		Route::post('toBePaidCustomSearched', 'PurchaseController@customSearched')->name('toBePaidCustomSearched');

		Route::post('partial-purchase-payment','PurchaseController@partialPurchasePayment')->name('partialPurchasePayment');
		Route::post('pay-partial-purchase-payment','PurchaseController@payPartialPurchasePayment')->name('payPartialPurchasePayment');
		Route::post('full-purchase-payment','PurchaseController@fullPurchasePayment')->name('fullPurchasePayment');
		Route::post('pay-full-purchase-payment','PurchaseController@payFullPurchasePayment')->name('payFullPurchasePayment');
		Route::get('purchase-payment-list/{id}','PurchaseController@purchasePaymentList')->name('purchasePaymentList');
		Route::post('purchase-search-by-month','PurchaseController@purchaseSearchByMonth')->name('purchaseSearchByMonth');
		Route::resource('purchase','PurchaseController');
		Route::resource('vat','VatController');
		Route::resource('setting','SettingController');
		Route::resource('bank','BankController');
		Route::post('monthly-payment-report','PaymentController@monthlyPaymentReport')->name('monthlyPaymentReport');
		Route::post('yearly-payment-report','PaymentController@yearlyPaymentReport')->name('yearlyPaymentReport');
		Route::post('payment/custom-date-search','PaymentController@customDateSearch')->name('customDateSearch');
		Route::resource('payment','PaymentController');
		Route::get('payment-filter-date','PaymentController@paymentFilterAccDate')->name('payment-filter-date');
		Route::post('payment/payment-modal','PaymentController@paymentModal')->name('paymentModal');

		Route::post('received/monthly-received-report','ReceivedController@monthlyReceivedReport')->name('monthlyReceivedReport');
		Route::post('received/yearly-received-report','ReceivedController@yearlyReceivedReport')->name('yearlyReceivedReport');
		Route::post('received/receipt-modal','ReceivedController@receiptModal')->name('receiptModal');		
		Route::post('received/custom-received-search','ReceivedController@customReceivedSearch')->name('customReceivedSearch');
		Route::resource('received','ReceivedController');
		Route::post('daybook/monthly-report','DayBookController@monthlyReport')->name('monthlydaybookReport');
		Route::post('daybook/yearly-report','DaybookController@yearlyReport')->name('yearlydaybookReport');
		Route::post('saerch-daybook','DayBookController@searchDaybook')->name('searchDaybook');
		Route::resource('day-book','DayBookController');
		Route::post('tds/monthlty-tds-report','TdsController@monthlyTdsReport')->name('monthlyTdsReport');
		
		Route::get('tds-to-be-paid','TdsController@tdsToBePaid')->name('tdsToBePaid');

		Route::resource('tds','TdsController');
		Route::resource('balance','BalanceController');
	});
});