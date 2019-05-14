<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                
                    <div class="alert alert-danger" role="alert" v-show="errorSale">
                       <div v-for="error in errorShowSale" :key="error">
                            <strong class="text-danger" v-text="error"></strong>
                       </div>
                    </div>
               
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ventas
                    </div>
                    <!-- Lista de Ventas -->
                    <template v-if="list==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criteria">
                                          <option value="type_voucher">Tipo de Comprobante</option>
                                          <option value="num_voucher">Número de Comprobante</option>
                                          <option value="date_hour">Fecha-Hora</option>
                                        </select>
                                        <input type="text" class="form-control" v-model="search" placeholder="Texto a buscar" @keyup.enter="lists_sale(1,search,criteria)">
                                        <button type="submit" class="btn btn-primary" @click="lists_sale(1,search,criteria)"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Usuario</th>
                                            <th>Cliente</th>
                                            <th>Tipo Docuemnto</th>
                                            <th>N° Docuemnto</th>
                                            <th>Serie Docuemnto</th>
                                            <th>Fecha-Hora</th>
                                            <th>Total</th>
                                            <th>Impuesto</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="sale in sales" :key="sale.id">
                                            <td>
                                                <button type="button" @click="showSale(sale.id)" class="btn btn-success btn-sm" title="Ver">
                                                  <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <button type="button" @click="pdfSale(sale.id)" class="btn btn-info btn-sm" title="PDF">
                                                  <i class="icon-doc"></i>
                                                </button> &nbsp;
                                            </td>
                                            <td v-text="sale.user_name"></td>
                                            <td v-text="sale.name"></td>
                                            <td v-text="sale.type_voucher"></td>
                                            <td v-text="sale.num_voucher"></td>
                                            <td v-text="sale.serie_voucher"></td>
                                            <td v-text="sale.date_hour"></td>
                                            <td v-text="sale.total"></td>
                                            <td v-text="sale.tax"></td>
                                            <td v-text="sale.state"></td>
                                <!--                                     <td>
                                                <div v-if="income.state">
                                                    <span class="badge badge-success">Registrado</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Anulado</span>
                                                </div>
                                            </td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="nextPage(pagination.current_page - 1, search, criteria)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="nextPage(page, search, criteria)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="nextPage(pagination.current_page + 1, search, criteria)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                    <!-- Fin Lista de Ventas -->
                    <!-- Mostrar Ventas -->
                    <template v-else="list==2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                       <label for="">Cliente</label>
                                       <p v-text="name"></p>                                     
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto</label>
                                    <p v-text="tax"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Tipo de Comprobante</label>
                                    <p v-text="type_voucher"></p>    
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="">Serie de Comprobante</label>
                                       <p v-text="serie_voucher"></p> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="">Núemro de Comprobante</label>
                                       <p v-text="num_voucher"></p>     
                                    </div>
                                </div>
                           </div> 
                           <div class="form-group row border">
                                <div class="table-responsive col-md-12" style="margin-top: 10px;">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Descuento</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="detail_sales.length">
                                            <tr v-for="detail in detail_sales" :key="detail.id">
                                                <td v-text="detail.product_name">
                                                </td>
                                                <td v-text="detail.price">
                                                </td>
                                                <td v-text="detail.discount">
                                                </td>
                                                <td v-text="detail.quantity">
                                                </td>
                                                <td>
                                                    {{ (detail.price*detail.quantity).toFixed(2) }}
                                                </td>
                                            </tr>

                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" class="text-right"><strong>Total Parcial</strong></td>
                                                <td>$ {{ total_partial = (total - total_tax).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" class="text-right"><strong>Total Impuesto</strong></td>
                                                <td>$ {{ total_tax = ((total*tax)).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" class="text-right"><strong>Total Neto</strong></td>
                                                <td>$ {{ total }}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    No hay productos registrados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                           </div> 
                           <div class="form-group row">
                               <div class="col-md-12">
                                   <button type="button" class="btn btn-secondary" @click="closeFormSale()">Cerrar</button>
                               </div>
                           </div>
                        </div>
                    </template>
                    <!-- Fin Mostrar Ventas -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
    	data() {
    		return{
                sale_id: 0,
                client_id: 0,
                name: '',
    			type_voucher: 'Boleta',
                serie_voucher: '',
                num_voucher: '',
                tax: 0.16,
    			total: 0.00,
                total_tax: 0.0,
                total_partial: 0.0,
                sales: [],
                clients: [],
                detail_sales: [],
                list: 1, 
    			modal: 0,
    			titleModal: '',
    			typeAction: 0,
                errorSale: 0,
                errorShowSale: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },

                offset: 3,
                criteria: 'num_voucher',
                search: '',
                criteria_p: 'name',
                search_p: '',
                products: [],
                product_id: 0,
                code: '',
                product_name: '',
                price: 0,
                quantity: 0,
                discount: 0, 
                stock: 0
    		}
    	},
        components:{
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación.
            pagesNumber: function(){
                if(!this.pagination.to){
                    return[];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++; 
                }
                return pagesArray;
            },
            calculateDiscount: function(){
                var total_discount = 0.0;
                // var i = 0;
                    total_discount = this.detail_sales[0].price*this.detail_sales[0].quantity*(this.detail_sales[0].discount/100);

                return total_discount;
            },
            calculateTotal: function(){
                var result = 0.0;
                for(var i=0; i<this.detail_sales.length; i++){
                    result = result+(this.detail_sales[i].price*this.detail_sales[i].quantity-(this.detail_sales[i].price*this.detail_sales[i].quantity*(this.detail_sales[i].discount/100)))
                }
                return result;
            }
        },
    	methods : {
    		lists_sale(page,search,criteria){
    			let me = this;
                var url = '/ventas?page=' + page + '&search='+ search + '&criteria='+ criteria;
    			axios.get(url).then(function (response){

                    var result = response.data;

    				me.sales = result.sales.data;
                    me.pagination = result.pagination;

    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            pdfSale(id){
                window.open('/venta/pdf/' + id + ',' + '_blank');
            },
            nextPage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                me.lists_sale(page,search,criteria);
            },
            showFormSale(){
                let me = this;
                this.list = 0;
                //clear form
                me.provider_id = 0;
                me.type_voucher = 'Boleta';
                me.serie_voucher = '';
                me.num_voucher = '';
                me.tax = 0.16;
                me.total = 0;
                me.product_id = 0;
                me.producto = '';
                me.quantity = 0;
                me.price = 0;
                me.detail_sales = [];
            },
            closeFormSale(){
                let me = this;
                this.list = 1;               
            },
            showSale(id){
                let me=this;
                me.list = 2;

                //obtener los datos del ingreso
                var arraysaleT = [];

                var url = '/venta/detalle_venta?id=' + id;

                axios.get(url).then(function (response){

                    var result = response.data;

                    arraysaleT = result.sale;
                    
                    me.name = arraysaleT[0]['name'];
                    me.type_voucher = arraysaleT[0]['type_voucher'];
                    me.serie_voucher = arraysaleT[0]['serie_voucher'];
                    me.num_voucher = arraysaleT[0]['num_voucher'];
                    me.tax = arraysaleT[0]['tax'];
                    me.total = arraysaleT[0]['total'];

                }).catch(function (error){
                    console.log(error);
                });    

                // //obtener los datos del detalle
                var urld = '/venta/detalle_producto?id=' + id;

                axios.get(urld).then(function (response){

                    var result = response.data;
                    me.detail_sales = result.datailsales;

                }).catch(function (error){
                    console.log(error);
                });     
            }
    	},
        mounted() {
            this.lists_sale(1,this.search,this.criteria);
        }
    }

</script>

<style>
/* 	.modal-content{
		width: 100% !important;
		position: adsolute !important;
	}

	.show{
		display: list-item !important;
		opacity: 1 !important;
		position: adsolute important;
		background-color: #3c29297a !important;
	} */
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px){
        .btnadd{
            margin-top: 2rem;
            margin-bottom: 10px;
        }
    }
</style>		