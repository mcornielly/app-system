<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                
                    <div class="alert alert-danger" role="alert" v-show="errorIncome">
                       <div v-for="error in errorShowIncome" :key="error">
                            <strong class="text-danger" v-text="error"></strong>
                       </div>
                    </div>
               
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresos
                    </div>
                    <!-- Lista de Ingresos -->
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
                                        <input type="text" class="form-control" v-model="search" placeholder="Texto a buscar" @keyup.enter="lists_income(1,search,criteria)">
                                        <button type="submit" class="btn btn-primary" @click="lists_income(1,search,criteria)"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Usuario</th>
                                            <th>Proveedor</th>
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
                                        <tr v-for="income in incomes" :key="income.id">
                                            <td>
                                                <button type="button" @click="showIncome(income.id)" class="btn btn-success btn-sm" title="Ver">
                                                  <i class="icon-eye"></i>
                                                </button> &nbsp;
                                            </td>
                                            <td v-text="income.user_name"></td>
                                            <td v-text="income.name"></td>
                                            <td v-text="income.type_voucher"></td>
                                            <td v-text="income.num_voucher"></td>
                                            <td v-text="income.serie_voucher"></td>
                                            <td v-text="income.date_hour"></td>
                                            <td v-text="income.total"></td>
                                            <td v-text="income.tax"></td>
                                            <td v-text="income.state"></td>
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
                    <!-- Fin Lista de Ingresos -->
                    <!-- Mostrar Ingresos -->
                    <template v-else="list==2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                       <label for="">Proveedor</label>
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
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="detail_incomes.length">
                                            <tr v-for="detail_income in detail_incomes" :key="detail_income.id">
                                                <td v-text="detail_income.product_name">
                                                </td>
                                                <td v-text="detail_income.price">
                                                </td>
                                                <td v-text="detail_income.quantity">
                                                </td>
                                                <td>
                                                    {{ (detail_income.price*detail_income.quantity).toFixed(2) }}
                                                </td>
                                            </tr>

                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" class="text-right"><strong>Total Parcial</strong></td>
                                                <td>$ {{ total_partial = (total - total_tax).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" class="text-right"><strong>Total Impuesto</strong></td>
                                                <td>$ {{ total_tax = ((total*tax)).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" class="text-right"><strong>Total Neto</strong></td>
                                                <td>$ {{ total }}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="4">
                                                    No hay productos registrados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                           </div> 
                           <div class="form-group row">
                               <div class="col-md-12">
                                   <button type="button" class="btn btn-secondary" @click="closeFormIncome()">Cerrar</button>
                               </div>
                           </div>
                        </div>
                    </template>
                    <!-- Fin Mostrar Ingresos -->
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
                income_id: 0,
                provider_id: 0,
                provider_name: '',
    			name: '',
    			type_voucher: 'Boleta',
                serie_voucher: '',
                num_voucher: '',
                tax: 0.16,
    			total: 0.00,
                total_tax: 0.0,
                total_partial: 0.0,
                incomes: [],
                providers: [],
                detail_incomes: [],
                list: 1, 
    			modal: 0,
    			titleModal: '',
    			typeAction: 0,
                errorIncome: 0,
                errorShowIncome: [],
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
                quantity: 0
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
            calculateTotal: function(){
                var result = 0.0;
                for(var i=0; i<this.detail_incomes.length; i++){
                    result = result+(this.detail_incomes[i].price*this.detail_incomes[i].quantity)
                }
                return result;
            }
        },
    	methods : {
    		lists_income(page,search,criteria){
    			let me = this;
                var url = '/ingresos?page=' + page + '&search='+ search + '&criteria='+ criteria;
    			axios.get(url).then(function (response){

                    var result = response.data;

    				me.incomes = result.incomes.data;
                    me.pagination = result.pagination;

    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            nextPage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                me.lists_income(page,search,criteria);
            },
            showFormIncome(){
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
                me.detail_incomes = [];
            },
            closeFormIncome(){
               this.list = 1;
            },
            showIncome(id){
                let me=this;
                me.list = 2;

                //obtener los datos del ingreso
                var arrayincomeT = [];

                var url = '/ingreso/detalle_ingreso?id=' + id;

                axios.get(url).then(function (response){

                    var result = response.data;
                    arrayincomeT = result.incomes;

                    me.name = arrayincomeT[0]['name'];
                    me.type_voucher = arrayincomeT[0]['type_voucher'];
                    me.serie_voucher = arrayincomeT[0]['serie_voucher'];
                    me.num_voucher = arrayincomeT[0]['num_voucher'];
                    me.tax = arrayincomeT[0]['tax'];
                    me.total = arrayincomeT[0]['total'];

                }).catch(function (error){
                    console.log(error);
                });    

                //obtener los datos del detalle
                var urld = '/ingreso/detalle_producto?id=' + id;

                axios.get(urld).then(function (response){

                    var result = response.data;
                    me.detail_incomes = result.detailincomes;

                }).catch(function (error){
                    console.log(error);
                });     
            }
    	},
        mounted() {
            this.lists_income(1,this.search,this.criteria);
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