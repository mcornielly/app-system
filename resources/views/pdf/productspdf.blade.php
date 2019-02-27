<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">  
	<title>Reporte de Productos</title>
	<!-- Main styles for this application -->
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    {{-- {!! Html::style('css/pdf.css') !!} --}}
{{-- 	<style>
		body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.875rem;
            font-weight: normal;
            line-height: 1.5;
            color: #151b1e;
            background: #ffff;           
        }
        .table {
            display: table;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }
        .table-bordered {
            border: 1px solid #c2cfd6;
        }
        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #c2cfd6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #c2cfd6;
        }
        .table-bordered thead th, .table-bordered thead td {
            border-bottom-width: 2px;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #c2cfd6;
        }
        th, td {
            display: table-cell;
            vertical-align: inherit;
        }
        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: left;
        }
        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .izquierda{
            float:left;
        }
        .derecha{
            float:right;
        }
	</style> --}}
</head>
<body>
	<div>
		<h3>Lista de Artículos <span class="text-right">{{ now() }}</span></h3>
	</div>
	<div>
		<table class="table table-bordered table-striped table-sm">
		    <thead>
		        <tr>
		            <th>Código</th>
		            <th>Nombre</th>
		            <th>Categoría</th>
		            <th>Precio</th>
		            <th>Stock</th>
		            <th>Descripción</th>
		            <th>Estado</th>
		        </tr>
		    </thead>
		    <tbody>
		    	@foreach ($products as $product)
		        <tr>
		            <td>{{ $product->code }}</td>
		            <td>{{ $product->name }}</td>
		            <td>{{ $product->category_name }}</td>
		            <td>{{ $product->price }}</td>
		            <td>{{ $product->stock }}</td>
		            <td>{{ $product->description }}</td>
		            <td>{{ $product->condition? 'Activo' : 'Desactivado' }}</td>
		        </tr>
		        @endforeach
		    </tbody>
		</table>
	</div>
	<div class="text-left">
		<p><strong>Total Registros: <span>{{ $count_products }}</span></strong></p>
	</div>
</body>
</html>