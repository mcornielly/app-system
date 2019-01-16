<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">

                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" class="btn btn-secondary" @click="showFormIncome()">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- Lista de Ingresos -->
                    <template v-if="list">
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
                                            <th>Impuesto</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="income in incomes" :key="income.id">
                                            <td>
                                                <button type="button" @click="openModal('income', 'update', income)" class="btn btn-success btn-sm">
                                                  <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <template v-if="income.state=='Registrado'">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="disableIncome(income.id)">
                                                      <i class="icon-trash"></i>
                                                    </button>
                                                </template>    
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
                    <!-- Formulario de Detalle de Ingresos -->
                    <template v-else>
                        <div class="card-body">
                           <div class="form-group row border">
                               <div class="col-md-9">
                                   <div class="form-group">
                                       <label for="">Proveedor(*)</label>
                                       <v-select
                                            :on-search="selectProvider"
                                            label="name"
                                            :options="providers"
                                            placeholder="Buscar Proveedores"
                                            :onChange="getDataProviders"></v-select>                                     
                                   </div>
                               </div>
                               <div class="col-md-3">
                                   <label for="">Impuesto(*)</label>
                                   <input type="text" class="form-control" v-model="tax">
                               </div>
                               <div class="col-md-4">
                                   <label for="">Tipo de Comprobante(*)</label>
                                   <select name="" id="" class="form-control" v-model="type_voucher">
                                       <option value="">Seleccione</option>
                                       <option value="Boleta">Boleta</option>
                                       <option value="Factura">Factura</option>
                                       <option value="Ticket">Ticket</option>
                                   </select>
                               </div>
                               <div class="col-md-4">
                                   <div class="form-group">
                                       <label for="">Serie de Comprobante</label>
                                       <input type="text" class="form-control" v-model="serie_voucher" placeholder="000x">
                                   </div>
                               </div>
                               <div class="col-md-4">
                                   <div class="form-group">
                                       <label for="">Núemro de Comprobante(*)</label>
                                       <input type="text" class="form-control" v-model="num_voucher" placeholder="000xx">
                                   </div>
                               </div>
                           </div> 
                           <div class="form-group row border">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Producto</label>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" v-model="code" @keyup.enter="searchProduct()"placeholder="Ingresar producto">
                                            <button class="btn btn-primary">...</button>
                                            <input type="text" readonly="true" class="form-control" v-model="product_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Precio</label>
                                        <input type="number" step="any" class="form-control" v-model="price">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Cantidad</label>
                                        <input type="number" class="form-control" v-model="quantity">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-comtrol">
                                        <button class="btn btn-success form-control btnadd">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                </div>
                           </div> 
                           <div class="form-group row border">
                                <div class="table-responsive col-md-12" style="margin-top: 10px;">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Artículo</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    Producto Nombre
                                                </td>
                                                <td>
                                                    <input type="number" value="3" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="number" value="2" class="form-control">
                                                </td>
                                                <td>
                                                    $ 6.00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    Producto Nombre
                                                </td>
                                                <td>
                                                    <input type="number" value="3" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="number" value="2" class="form-control">
                                                </td>
                                                <td>
                                                    $ 6.00
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" class="text-right"><strong>Total Parcial</strong></td>
                                                <td>$ 5</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" class="text-right"><strong>Total Impuesto</strong></td>
                                                <td>$ 1</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" class="text-right"><strong>Total Neto</strong></td>
                                                <td>$ 6</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                           </div> 
                           <div class="form-group row">
                               <div class="col-md-12">
                                   <button type="button" class="btn btn-secondary" @click="closeFormIncome()">Cerrar</button>
                                   <button type="button" class="btn btn-primary" @click="insertIncome()">Registrar Ingreso</button>
                               </div>
                           </div>
                        </div>
                    </template>
                    <!-- Fin Formulario de Detalle de Ingresos -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'show' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="titleModal"></h4>
                            <button type="button" class="close" @click="closeModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Nombre del Cliente" v-model="name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Tipo de Documento</label>
                                    <div class="col-md-9">
                                        <select v-model="type_document" class="form-control">
                                            <option value="">Seleccione un Tipo de Documento</option>
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                            <option value="J">J</option>
                                            <option value="G">G</option>
                                        </select>
                                        <!-- <input type="text" class="form-control" placeholder="Ingrese Tipo de Documento" v-model="type_document"> -->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">N Documento</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Ingrese número de documento" v-model="num_document">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Ingrese Dirección" v-model="address">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">N de Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Ingrese número de teléfono" v-model="num_phone">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Corre Electrónico</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" v-model="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Rol (*)</label>
                                    <div class="col-md-9">
                                        <select v-model="role_id" class="form-control">
                                            <option value="0">Seleccione un Rol</option>
                                            <option v-for="role in roles" :key="role.id" :value="role.id" v-text="role.name"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Nombre de Usuario (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Ingrese Nombre de Usuario" v-model="user_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Password (*)</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" placeholder="Ingrese el Password" v-model="password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-md-3"></div>  
                                    <div class="col col-md-9" v-show="errorClient">
                                       <div v-for="error in errorShowClient" :key="error">
                                            <strong class="text-danger" v-text="error"></strong>
                                       </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==1" 
                            @click="createClient()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==2" @click="updateClient()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

        </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
    	data() {
    		return{
                income_id: 0,
                provider_id: 0,
    			name: '',
    			type_voucher: 'Boleta',
                serie_voucher: '',
                num_voucher: '',
                tax: 0.16,
    			total: 0.00,
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
                product: [],
                product_id: 0,
                code: '',
                product_name: '',
                price: 0,
                amount: 0
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
            selectProvider(search, loading){
                let me = this;
                loading(true)

                var url = '/proveedor/seleccionar-proveedor?filter='+search;
                axios.get(url).then(function (response){

                    var result = response.data;
                    q: search
                    me.providers = result.providers;
                    loading(false)


                }).catch(function (error){
                    console.log(error);
                });
            },
            getDataProviders(val1){
                let me = this;
                me.loading = true;
                me.provider_id = val1.id;
            },
            searchProduct(){
                let me = this;
                var url = '/producto/buscar-producto?filter='+me.code;

                axios.get(url).then(function(response){
                    var result = response.data;
                    me.product = result.product;

                    if(me.product.length>0){
                        me.product_name = me.product[0]['name'];
                        me.product_id = me.product[0]['id'];
                    }else{
                        me.product_name = "No existe producto";
                        me.product_id = 0;
                    }
                })
                .catch(function(error){
                    console.log(error);
                });

            },
            nextPage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                me.lists_income(page,search,criteria);
            },
    		createClient(){
                if (this.validateClient()){
                    return;
                }

    			let me = this;

                axios.post('usuarios',{
    			    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'num_phone': this.num_phone,
                    'email': this.email,
                    'user_name': this.user_name,
                    'password': this.password, 
                    'role_id': this.role_id
    			}).then(function (response){
    				me.closeModal();
    				me.lists_clients(1,'','nombre');
    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            updateClient(){
                console.log(this.role_id, this.client_id);
                if (this.validateClient()){
                    return;
                }

                let me = this;
                axios.put('/usuarios/actualizar',{
                    'id': this.client_id,
                    'role_id': this.role_id,
                    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'num_phone': this.num_phone,
                    'email': this.email,
                    'user_name': this.user_name,
                    'password': this.password 
                }).then(function (response){
                    me.closeModal();
                    me.lists_clients(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },
            showFormIncome(){
                this.list = 0;
            },
            closeFormIncome(){
                this.list = 1;
            },
            enableIncome(id){

                const swalWithBootstrapButtons = Swal.mixin({
                  confirmButtonClass: 'btn btn-success',
                  cancelButtonClass: 'btn btn-danger',
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                  title: 'Esta seguro que desea Activar el Usuario?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, Activar!',
                  cancelButtonText: 'No, Cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {
                        console.log(id);

                        let me = this;

                        axios.put('/usuario/activar',{
                            'id': id,
                        }).then(function (response){
                            me.lists_clients(1,'','nombre');
                            swalWithBootstrapButtons(
                              'Activada!',
                              'La Usuario fue Activado..!',
                              'success'
                            )
                        }).catch(function (error){
                            console.log(error);
                        });
                  } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                  }
                })

            },
            disableIncome(id){

                const swalWithBootstrapButtons = Swal.mixin({
                  confirmButtonClass: 'btn btn-success',
                  cancelButtonClass: 'btn btn-danger',
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                  title: 'Esta seguro que desea desactivar el Usuario?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, Desactivar!',
                  cancelButtonText: 'No, Cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                        let me = this;

                        axios.put('/usuario/desactivar',{
                            'id': id
                        }).then(function (response){
                            me.lists_clients(1,'','nombre');

                            swalWithBootstrapButtons(
                              'Desactivado!',
                              'El Usuario fue Desactivado..!',
                              'success'
                            )
                        }).catch(function (error){
                            console.log(error);
                        });
                  } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                  ){

                  }
              })
            },
            validateClient(){
                this.errorIncome = 0;
                this.errorShowIncome = [];

                if (!this.name) this.errorShowIncome.push("El nombre del usuario no puede estar vacio.");
                if (!this.user_name) this.errorShowIncome.push("El Username no puede estar vacio.");
                if (!this.password) this.errorShowIncome.push("El Password no puede estar vacio.");
                if (this.role_id == 0) this.errorShowIncome.push("Debe Seleccionar un rol.");
                if(this.errorShowIncome.length) this.errorIncome = 1;

                return this.errorIncome;
            },
    		closeModal(){;
    			this.modal=0;
                this.titleModal='';
                this.name='';
                this.type_document='V';
                this.num_document='';
                this.address='';
                this.num_phone='';
                this.email='';
                this.role_id= 0;
                this.role_name = '';
                this.password = '';
                this.errorClient = 0;
    		},	
    		openModal(modelo, action, data = []){
                this.selectRole();
    			switch(modelo){
    				case 'client':
    				{
    					switch(action){
    						case 'store':
    						{
                                this.errorClient = 0;
                                this.errorShowClient = [];
    							this.modal = 1;
		    					this.titleModal = 'Registrar Usuario';
                                this.typeAction = 1;
				    			this.name = '';
				    			this.type_document = 'V';
				    			this.num_document = '';
				    			this.address = '';
				    			this.num_phone = '';
				    			this.email = '';
                                this.role_id = 0;
                                this.user_name = '';
                                this.password = '';
		    					break;
    						}
    						case 'update':
    						{
                                // console.log(data);
                                this.errorClient = 0;
                                this.errorShowClient = [];
		    				 	this.modal = 1;
		    					this.titleModal = 'Actualizar Actualizar';
                                this.typeAction = 2;
                                this.client_id = data['id'];
                                this.name = data['name'];
                                this.type_document = data['type_document'];
                                this.num_document = data['num_document'];
                                this.address = data['address'];
                                this.num_phone = data['num_phone'];
                                this.email = data['email'];
                                this.role_id = data['role_id'];
                                this.user_name = data['user_name'];
                                this.password = data['password'];
		    					break;	
    						}
    					}
    				}
    			}
    		}
    	},
        mounted() {
            this.lists_income(1,this.search,this.criteria);
        }
    }

</script>

<style>
	.modal-content{
		width: 100% !important;
		position: adsolute !important;
	}

	.show{
		display: list-item !important;
		opacity: 1 !important;
		position: adsolute important;
		background-color: #3c29297a !important;
	}
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