<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .invoice-container {
            width: 100%;
            background-color: white;
        }

        .header,
        .footer {
            text-align: center;
        }

        .header img {
            width: 150px;
        }

        .company-info,
        .billing-info,
        .product-info,
        .total-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .company-info td,
        .billing-info td,
        .product-info th,
        .product-info td,
        .total-info td {
            padding: 10px;
        }

        .company-info {
            margin-bottom: 10px;
        }

        .billing-info td {
            width: 50%;
            vertical-align: top;
        }

        .product-info th {
            background-color: #f2f2f2;
            border: 1px solid;
        }

        .product-info td,
        .product-info th {
            text-align: left;
            border: 1px solid;
        }

        .total-info td {
            text-align: right;
        }

        .total-info tr td:first-child {
            text-align: left;
        }

        /* Specific column width adjustments */
        .customer-no {
            white-space: nowrap;
        }

        .product-info th.po-no {
            width: 200px;
            /* Increase width of P.O column */
        }

        .product-info th.terms {
            width: 100px;
            /* Reduce width of Terms column */
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <!-- Company and Invoice Header -->
        <table class="company-info">
            <tr>
                <td>
                    <img width="150px;" height="69px;" src="{{ $logo }}" alt="logo">
                </td>
                <td>
                    <strong>PASTRY MART PTE LTD</strong><br>
                    1 Senoko Avenue #05-05 FoodAxis@Senoko<br>
                    Singapore 758297<br>
                    Tel: +65 6694 1878 | Fax: +65 6694 1883<br>
                    Website: www.pastrymart.sg
                </td>
                <td>
                    <img width="150px;" height="50px;" src="{{ $accreditation }}" alt="accreditation"> <br>
                    Co. Reg No.: 200009544M <br>
                    GST Reg No.: 20-0009544-M
                </td>
                <td>
                    <strong>TAX INVOICE</strong><br>
                    NO.: {{ $order->DocNum }}<br>
                    DATE: {{ \Carbon\Carbon::parse($order->DocDueDate)->format('d/m/Y') }}<br>
                    PAGE: Page 1 of 1
                </td>
            </tr>
        </table>

        <!-- Billing and Delivery Information -->
        <table class="billing-info">
            <tr>
                <td>
                    <strong>BILL TO:</strong><br>
                    {{ $bp->ContactPerson }} <br>
                    {{ $order->AddressExtension['BillToBlock'] ?? '' }} <br>
                    {{ $order->AddressExtension['BillToStreet'] ?? '' }} <br>
                    Singapore-{{ $order->AddressExtension['BillToZipCode'] ?? '' }}
                </td>
                <td>
                    <strong>DELIVER TO:</strong><br>
                    {{ $bp->ContactPerson }} <br>
                    {{ $order->AddressExtension['ShipToBlock'] ?? '' }} <br>
                    {{ $order->AddressExtension['ShipToStreet'] ?? '' }} <br>
                    Singapore-{{ $order->AddressExtension['ShipToZipCode'] ?? '' }}
                </td>
            </tr>
        </table>

        <!-- PO Information -->
        <table class="product-info">
            <thead>
                <tr>
                    <th class="customer-no">CUSTOMER NO.</th>
                    <th class="po-no">P.O NO.</th>
                    <th>D.O NO.</th>
                    <th>S.E</th>
                    <th class="terms">TERMS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $bp->CardCode }}</td>
                    <td>{{ $order->NumAtCard }}</td>
                    <td></td>
                    <td>{{ $salesPerson->SalesEmployeeName }}</td>
                    <td>{{ $paymentTerm->PaymentTermsGroupName ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Product Information -->
        <table class="product-info">
            <thead>
                <tr>
                    <th>ITEM NO.</th>
                    <th>DESCRIPTION</th>
                    <th>QTY UOM</th>
                    <th>UNIT PRICE</th>
                    <th>AMOUNT (S$)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->DocumentLines as $documentLine)
                    <tr>
                        <td>{{ $documentLine['ItemCode'] }}</td>
                        <td>{{ $documentLine['ItemDescription'] }}</td>
                        <td>{{ $documentLine['Quantity'] }} {{ $documentLine['UoMCode'] }}</td>
                        <td>{{ $documentLine['Price'] }}</td>
                        <td>{{ $documentLine['LineTotal'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Information -->
        <table class="total-info">
            <tr>
                <td>SUB TOTAL:</td>
                <td>{{ number_format($order->DocTotal - $order->VatSum, 2) }}</td>
            </tr>
            <tr>
                <td>GST @ 9.00%:</td>
                <td>{{ number_format($order->VatSum, 2) }}</td>
            </tr>
            <tr>
                <td><strong>TOTAL:</strong></td>
                <td><strong>{{ number_format($order->DocTotal, 2) }}</strong></td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>GOODS SOLD ARE NOT RETURNABLE & EXCHANGEABLE</p>
            <p>RECEIVED IN GOOD ORDER AND CONDITION.</p>
            <p><img width="150px" height="100px;" src="{{ $esign }}" alt="E-Signature"></p>
            <p>NAME: {{ $order->{config('udf.signed_name')} }}</p>
            <p>REMARKS: {{ $order->{config('udf.order_remarks')} }}</p>
            <hr>
            <strong>CUSTOMER'S SIGNATURE</strong>
        </div>
    </div>

</body>

</html>
