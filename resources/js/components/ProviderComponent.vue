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
                        <i class="fa fa-align-justify"></i> Proveedores
                        <button type="button" class="btn btn-secondary float-sm-right btn-movil" @click="openModal('client', 'store')">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                      <option value="name">Nombre</option>
                                      <option value="type_document">Tipo de Documento</option>
                                      <option value="num_document">N Docuemento</option>
                                      <option value="address">Dirección</option>
                                      <option value="num_phone">Telefono</option>
                                      <option value="email">Email</option>
                                    </select>
                                    <input type="text" class="form-control" v-model="search" placeholder="Texto a buscar" @keyup.enter="lists_clients(1,search,criteria)">
                                    <button type="submit" class="btn btn-primary" @click="lists_clients(1,search,criteria)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>Tipo de Documento</th>
                                        <th>N° Docuemnto</th>
                                        <th>Dirección</th>
                                        <th>N° Teléfono</th>
                                        <th>Email</th>
                                        <th>Nombre del Contacto</th>
                                        <th>N° Teléfono de Contacto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="client in clients" :key="client.id">
                                        <td>
                                            <button type="button" @click="openModal('client', 'update', client)" class="btn btn-warning btn-sm">
                                              <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="client.name"></td>
                                        <td v-text="client.type_document"></td>
                                        <td v-text="client.num_document"></td>
                                        <td v-text="client.address"></td>
                                        <td v-text="client.num_phone"></td>
                                        <td v-text="client.email"></td>
                                        <td v-text="client.contact_name"></td>
                                        <td v-text="client.contact_phone"></td>
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
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'show_' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-3 form-control-label">Nombre del Contacto</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Ingrese Nombre de Contacto" v-model="contact_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">N de Teléfono Contacto</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Ingrese Número de Contacto" v-model="contact_phone">
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
    export default {
    	data() {
    		return{
                client_id: 0,
    			name: '',
    			type_document: 'J',
    			num_document: '',
    			address: '',
    			num_phone: '',
    			email: '',
                contact_name: '',
                contact_phone: '',
    			clients: [],
    			modal: 0,
    			titleModal: '',
    			typeAction: 0,
                errorClient: 0,
                errorShowClient: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },

                offset: 3,
                criteria: 'name',
                search: ''

    		}
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
    		lists_clients(page,search,criteria){
    			let me = this;
                var url = '/proveedores?page=' + page + '&search='+ search + '&criteria='+ criteria;
    			axios.get(url).then(function (response){

                    var result = response.data;

    				me.clients = result.clients.data;
                    me.pagination = result.pagination;

    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            nextPage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                me.lists_clients(page,search,criteria);
            },
    		createClient(){
                if (this.validateClient()){
                    return;
                }

    			let me = this;

                axios.post('proveedores',{
    				'name': this.name,
    				'type_document': this.type_document,
    				'num_document': this.num_document,
    				'address': this.address,
    				'num_phone': this.num_phone,
    				'email': this.email,
                    'contact_name': this.contact_name,
                    'contact_phone': this.contact_phone
    			}).then(function (response){
    				me.closeModal();
    				me.lists_clients(1,'','nombre');
    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            updateClient(){
                if (this.validateClient()){
                    return;
                }

                let me = this;

                axios.put('/proveedor/actualizar',{
                    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'num_phone': this.num_phone,
                    'email': this.email,
                    'contact_name': this.contact_name,
                    'contact_phone': this.contact_phone, 
                    'id': this.client_id
                }).then(function (response){
                    me.closeModal();
                    me.lists_clients(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },
            validateClient(){
                this.errorClient = 0;
                this.errorShowClient = [];

                if (!this.name) this.errorShowClient.push("El nombre del Cliente no puede estar vacio.");
                if(this.errorShowClient.length) this.errorClient = 1;

                return this.errorClient;
            },
    		closeModal(){
    			this.modal=0;
    			this.titleModal='';
    			this.name='';
    			this.type_document='';
    			this.num_document='';
    			this.address='';
    			this.num_phone='';
    			this.email='';
                this.contact_name = '';
                this.contact_phone = '';
    		},	
    		openModal(modelo, action, data = []){
    			switch(modelo){
    				case 'client':
    				{
    					switch(action){
    						case 'store':
    						{
                                this.errorClient = 0;
                                this.errorShowClient = [];
    							this.modal = 1;
		    					this.titleModal = 'Registrar Proveedor';
                                this.typeAction = 1;
				    			this.name = '';
				    			this.type_document = 'J';
				    			this.num_document = '';
				    			this.address = '';
				    			this.num_phone = '';
				    			this.email = '';
                                this.contact_name = '';
                                this.contact_phone = '';
		    					break;
    						}
    						case 'update':
    						{
                                // console.log(data);
                                this.errorClient = 0;
                                this.errorShowClient = [];
		    				 	this.modal = 1;
		    					this.titleModal = 'Actualizar Proveedor';
                                this.typeAction = 2;
                                this.client_id = data['id'];
                                this.name = data['name'];
				    			this.type_document = data['type_document'];
				    			this.num_document = data['num_document'];
				    			this.address = data['address'];
				    			this.num_phone = data['num_phone'];
				    			this.email = data['email'];
                                this.contact_name = data['contact_name'];
                                this.contact_phone = data['contact_phone'];
		    					break;	
    						}
    					}
    				}
    			}
    		}
    	},
        mounted() {
            this.lists_clients(1,this.search,this.criteria);
        }
    }

</script>

<style>
	.modal-content{
		width: 100% !important;
		position: adsolute !important;
        margin-top: 15em; 
	}

	.show_{
		display: list-item !important;
		opacity: 1 !important;
		position: adsolute !important;
		background-color: #3c29297a !important;
	}

    @media screen and (min-width: 400px) {
        .btn-movil {
            float: right;
        }
    }
</style>		