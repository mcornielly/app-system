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
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" class="btn btn-secondary float-sm-right btn-movil" @click="openModal('category', 'store')">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                      <option value="name">Nombre</option>
                                      <option value="description">Descripción</option>
                                    </select>
                                    <input type="text" class="form-control" v-model="search" placeholder="Texto a buscar" @keyup.enter="lists_category(1,search,criteria)">
                                    <button type="submit" class="btn btn-primary" @click="lists_category(1,search,criteria)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                               <thead>
                                   <tr>
                                       <th>Opciones</th>
                                       <th>Nombre</th>
                                       <th>Descripción</th>
                                       <th>Estado</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <tr v-for="category in categories" :key="category.id">
                                       <td>
                                           <button type="button" @click="openModal('category', 'update', category)" class="btn btn-warning btn-sm">
                                             <i class="icon-pencil"></i>
                                           </button> &nbsp;
                           
                                           <template v-if="category.condition">
                                               <button type="button" class="btn btn-danger btn-sm" @click="disableCategory(category.id)">
                                                 <i class="icon-trash"></i>
                                               </button>
                                           </template>    
                                           
                                           <template v-else>
                                               <button type="button" class="btn btn-success btn-sm" @click="enableCategory(category.id)">
                                                   <i class="icon-check"></i>
                                               </button>
                                           </template>
                           
                                       </td>
                                       <td v-text="category.name"></td>
                                       <td v-text="category.description"></td>
                                       <td>
                                           <div v-if="category.condition">
                                               <span class="badge badge-success">Activo</span>
                                           </div>
                                           <div v-else>
                                               <span class="badge badge-danger">Desactivado</span>
                                           </div>
                                       </td>
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
                                        <input type="text" class="form-control" placeholder="Nombre de categoría" v-model="name">
                                        <div v-show="errorCategory">
                                           <div v-for="error in errorShowCategory" :key="error">
                                                <strong class="text-danger" v-text="error"></strong>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Ingrese Descripción" v-model="description">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==1" 
                            @click="createCategory()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==2" @click="updateCategory()">Actualizar</button>
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
                category_id: 0,
    			name: '',
    			description: '',
    			categories: [],
    			modal: 0,
    			titleModal: '',
    			typeAction: 0,
                errorCategory: 0,
                errorShowCategory: [],
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
    		lists_category(page,search,criteria){
    			let me = this;
                var url = '/categorias?page=' + page + '&search='+ search + '&criteria='+ criteria;
    			axios.get(url).then(function (response){

                    var result = response.data;

    				me.categories = result.categories.data;
                    me.pagination = result.pagination;

    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            nextPage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                me.lists_category(page,search,criteria);
            },
    		createCategory(){
                if (this.validateCategory()){
                    return;
                }

    			let me = this;

    			axios.post('categorias',{
    				'name': this.name,
    				'description': this.description
    			}).then(function (response){
    				me.closeModal();
    				me.lists_category(1,'','nombre');
    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            updateCategory(){

                let me = this;

                axios.put('/categorias/actualizar',{
                    'id': this.category_id,
                    'name': this.name,
                    'description': this.description
                }).then(function (response){
                    me.closeModal();
                    me.lists_category(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },
            enableCategory(id){

                const swalWithBootstrapButtons = Swal.mixin({
                  confirmButtonClass: 'btn btn-success',
                  cancelButtonClass: 'btn btn-danger',
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                  title: 'Esta seguro que desea Activar la Categoría?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, Activar!',
                  cancelButtonText: 'No, cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {
                        console.log(id);

                        let me = this;

                        axios.put('/categoria/activar',{
                            'id': id,
                        }).then(function (response){
                            me.lists_category(1,'','nombre');
                            swalWithBootstrapButtons(
                              'Activada!',
                              'La Categoria fue Activada..!',
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
            disableCategory(id){

                const swalWithBootstrapButtons = Swal.mixin({
                  confirmButtonClass: 'btn btn-success',
                  cancelButtonClass: 'btn btn-danger',
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                  title: 'Esta seguro que desea desactivar la Categoría?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, desactivar!',
                  cancelButtonText: 'No, cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                        let me = this;

                        axios.put('/categorias/desactivar',{
                            'id': id
                        }).then(function (response){
                            me.lists_category(1,'','nombre');

                            swalWithBootstrapButtons(
                              'Desactivado!',
                              'La Categoria fue Desactivada..!',
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
            validateCategory(){
                this.errorCategory = 0;
                this.errorShowCategory = [];

                if (!this.name) this.errorShowCategory.push("El nombre de la Categoría no puede estar vacio.");
                if(this.errorShowCategory.length) this.errorCategory = 1;

                return this.errorCategory;
            },
    		closeModal(){
    			this.modal=0;
    			this.titleModal="";
    			this.name='';
    			this.description='';
    		},	
    		openModal(modelo, action, data = []){
    			switch(modelo){
    				case 'category':
    				{
    					switch(action){
    						case 'store':
    						{
                                this.errorCategory = 0;
                                this.errorShowCategory = [];
    							this.modal = 1;
		    					this.titleModal = 'Registrar Categoría';
                                this.typeAction = 1;
		    					this.name = '';
		    					this.description = '';
		    					break;
    						}
    						case 'update':
    						{
                                // console.log(data);
                                this.errorCategory = 0;
                                this.errorShowCategory = [];
		    				 	this.modal = 1;
		    					this.titleModal = 'Actualizar Categoría';
                                this.typeAction = 2;
                                this.category_id = data['id'];
                                this.name = data['name'];
                                this.description = data['description'];
		    					break;	
    						}
    					}
    				}
    			}
    		}
    	},
        mounted() {
            this.lists_category(1,this.search,this.criteria);
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