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
        .header, .footer {
            text-align: center;
        }
        .header img {
            width: 150px;
        }
        .company-info, .billing-info, .product-info, .total-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .company-info td, .billing-info td, .product-info th, .product-info td, .total-info td {
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
        .product-info td, .product-info th {
            text-align: left;
            border: 1px solid;
        }
        .total-info td {
            text-align: right;
        }
        .total-info tr td:first-child {
            text-align: left;
        }
        .qr-code {
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <!-- Company and Invoice Header -->
        <table class="company-info">
            <tr>
                <td>
                   <img width="150px;" height="50px;" src="{{ asset('pastrymart-logo.png') }}" alt="logo">
                </td>
                <td>
                    <strong>PASTRY MART PTE LTD</strong><br>
                    1 Senoko Avenue #05-05 FoodAxis@Senoko<br>
                    Singapore 758297<br>
                    Tel: +65 6694 1878 | Fax: +65 6694 1883<br>
                    Website: www.pastrymart.sg
                </td>
                <td>
                    <img width="150px;" height="50px;" src="{{ asset('accreditation.jpg') }}" alt="accreditation"> <br>
                    Co. Reg No.: 200009544M <br>
                    GST Reg No.: 20-0009544-M
                </td>
                <td>
                    <strong>TAX INVOICE</strong><br>
                    NO.: 310204<br>
                    DATE: 14/09/2024<br>
                    PAGE: Page 1 of 1
                </td>
            </tr>
        </table>

        <!-- Billing and Delivery Information -->
        <table class="billing-info">
            <tr>
                <td>
                    <strong>BILL TO:</strong><br>
                    Mr. Francis Goh - HP: 9743 9351<br>
                    Blk 864 Woodlands St 83 #05-204<br>
                    Singapore - 730864
                </td>
                <td>
                    <strong>DELIVER TO:</strong><br>
                    Mr. Francis Goh - HP: 9743 9351<br>
                    Blk 864 Woodlands St 83 #05-204<br>
                    Singapore - 730864
                </td>
            </tr>
        </table>

        <!-- PO Information -->
        <table class="product-info">
            <thead>
                <tr>
                    <th>CUSTOMER NO.</th>
                    <th>P.O NO.</th>
                    <th>D.O NO.</th>
                    <th>TERMS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CPM-M5478</td>
                    <td></td>
                    <td></td>
                    <td>Cash (Bank transfer)</td>
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
                <tr>
                    <td>SDFASDF</td>
                    <td>Richs Gold Label Topping Cream (pkt x 907g)<br>Exp Date: 26/05/2025</td>
                    <td>10 Pkt</td>
                    <td>5.20</td>
                    <td>52.00</td>
                </tr>
                <tr>
                    <td>ASDFASDF</td>
                    <td>Paysan Breton Dairy Whipping Cream (pkt x 1ltr)<br>Exp Date: 21/11/2024</td>
                    <td>2 Pkt</td>
                    <td>7.41</td>
                    <td>14.82</td>
                </tr>
            </tbody>
        </table>

        <!-- Total Information -->
        <table class="total-info">
            <tr>
                <td>SUB TOTAL:</td>
                <td>66.82</td>
            </tr>
            <tr>
                <td>GST @ 9.00%:</td>
                <td>6.01</td>
            </tr>
            <tr>
                <td><strong>TOTAL:</strong></td>
                <td><strong>72.83</strong></td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>GOODS SOLD ARE NOT RETURNABLE & EXCHANGEABLE</p>
            <p>RECEIVED IN GOOD ORDER AND CONDITION.</p>
            <p><img class="qr-code" src="{{ asset('storage/signatures/PMSO_310770.png') }}" alt="QR Code"></p>
            <p>NAME: JOHN DOE</p>
            <hr>
            <strong>CUSTOMER'S SIGNATURE</strong>
        </div>
    </div>

</body>
</html>
