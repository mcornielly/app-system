<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reporte de Ventas</title>
     <!-- Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css"> -->
</head>
 <style>
        body {
        position: relative;
        width: 16cm;  
        height: 29.7cm; 
        margin: 0 auto; 
        color: #555555;
        background: #FFFFFF;
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /* font-family: SourceSansPro; */
        }

        #logo{
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }

        #imagen{
        width: 130px;
        height: 130px;
        padding-top: 80px;
        }
        

        #datos{
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        text-align: justify;
        }

        #encabezado{
        float: none;
        display: block;    
        margin: auto;
        width: 100%;
        text-align: center;
        font-size: 15px;
        }

        #fact{
        position: relative;
        float: right;
        margin-top: 5%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facarticulo thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #gracias{
        text-align: center; 
        }
    </style>
    <body>
    	@foreach($sale as $s)
        <header>
            <div id="datos">
                <p id="encabezado">
                    <b>Sistema de Ventas</b><br>Miguel Cornielly, Caracas - Venezuela<br>Telefono:(+58)4120272359<br>Email:mcornielly@gmail.com
                </p>
            </div>
            <div id="logo">
                <img src="img/logo_system.jpg" alt="" id="imagen">
            </div>
        </header>
        <br>
        <section style="margin-top: 50px;">
            <div style="float: right;">
                <table id="facliente">
                    <thead>
                        <tr>
                            <th id="fa" colspan="3"><strong>{{ $s->type_voucher }}</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">{{ $s->serie_voucher }}</td>
                            <td> | </td>
                            <td style="text-align: center;">{{ $s->num_voucher }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p id="cliente">Sr(a). {{ $s->name }}<br>
                            Documento: {{ $s->type_document }} - {{ $s->num_document }}<br>
                            Dirección: {{ $s->address }}<br>
                            Teléfono:  {{ $s->num_phone }}<br>
                            Email: {{ $s->email }}</p></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div>
                <table id="facvendedor">
                    <thead>
                        <tr id="fv">
                            <th>VENDEDOR</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="text-align: center;">
                            <td>{{ $s->user_name }}</td>
                            <td>{{ $s->created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
        	@endforeach
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th>CANT</th>
                            <th>DESCRIPCION</th>
                            <th>PRECIO UNIT</th>
                            <th>DESC.</th>
                            <th>PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($detail_sale as $detail)
                        <tr  style="text-align: right;">
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ $detail->description }}</td>
                            <td>{{ $detail->price }}</td>
                            <td>{{ $detail->discount }}</td>
                            <td>{{ $detail->quantity*$detail->price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    	@foreach($sale as $s)
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>SUBTOTAL</th>
                            <td  style="text-align: right;">{{ round($s->total-($s->total*$s->tax),2) }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>IVA</th>
                            <td  style="text-align: right;">{{ round($s->total*$s->tax,2) }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td  style="text-align: right;">{{ round($s->total) }}</td>
                        </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer>
	
</body>
</html>