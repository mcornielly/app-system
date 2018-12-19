<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">

                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Productos
                        <button type="button" class="btn btn-secondary" @click="openModal('product', 'store')">
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
                                    <input type="text" class="form-control" v-model="search" placeholder="Texto a buscar" @keyup.enter="lists_products(1,search,criteria)">
                                    <button type="submit" class="btn btn-primary" @click="lists_products(1,search,criteria)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
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
                                <tr v-for="product in products" :key="product.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="openModal('product', 'update', product)">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;

                                        <template v-if="product.condition">
                                            <button type="button" class="btn btn-danger btn-sm" @click="disableProduct(product.id)">
                                              <i class="icon-trash"></i>
                                            </button>
                                        </template>    
                                        
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="enableProduct(product.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                    </td>
                                    <td v-text="product.code"></td>
                                    <td v-text="product.name"></td>
                                    <td v-text="product.category_name"></td>
                                    <td v-text="product.price"></td>
                                    <td v-text="product.stock"></td>
                                    <td v-text="product.description"></td>
                                    <td>
                                        <div v-if="product.condition">
                                        	<span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                        	<span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categorias</label>
                                    <div class="col-md-9">
                                  		<select class="form-control" v-model="category_id">
                                  			<option value="0">Seleccione</option>
                                  			<option v-for="category in categories" :key="category.id" :value="category.id" v-text="category.name"></option>
                                  		</select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Código de Barras" v-model="code">
                                        <barcode :value="code" :options="{ format: 'EAN-13'}">
                                            Generando código de barras.
                                        </barcode>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre de Producto</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Nombre del Producto" v-model="name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="price">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="" v-model="stock">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Ingrese Descripción" v-model="description">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-md-3"></div>  
                                    <div class="col col-md-9" v-show="errorProduct">
                                       <div v-for="error in errorShowProduct" :key="error">
                                            <strong class="text-danger" v-text="error"></strong>
                                       </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==1" 
                            @click="createProduct()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==2" @click="updateProduct()">Actualizar</button>
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
import VueBarcode from 'vue-barcode';

    export default {
    	data() {
    		return{
    			product_id: 0,
                category_id: 0,
                category_name: '', 
                code: '',
    			name: '',
    			price: 0,
    			stock: 0,
    			description: '',
    			products: [],
    			modal: 0,
    			titleModal: '',
    			typeAction: 0,
                errorProduct: 0,
                errorShowProduct: [],
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
                search: '',
                categories: []

    		}
    	},
        components: {
            'barcode': VueBarcode
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
    		lists_products(page,search,criteria){
    			let me = this;
                var url = '/productos?page=' + page + '&search='+ search + '&criteria='+ criteria;
    			axios.get(url).then(function (response){

                    var result = response.data;

    				me.products = result.products.data;
                    me.pagination = result.pagination;

    			}).catch(function (error){
    				console.log(error);
    			});
    		},
    		selectCategory(){
    			let me = this;
                var url = '/categoria/seleccionar';
    			axios.get(url).then(function (response){
    				console.log(response);	

        			var result = response.data;
    				me.categories = result.categories;
             
    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            nextPage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                me.lists_products(page,search,criteria);
            },
    		createProduct(){
                if (this.validateProduct()){
                    return;
                }

    			let me = this;

    			axios.post('productos',{
                    'category_id': this.category_id,
                    'code': this.code,
    				'name': this.name,
                    'price': this.price,
                    'stock': this.stock,
    				'description': this.description
    			}).then(function (response){
    				me.closeModal();
    				me.lists_products(1,'','nombre');
    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            updateProduct(){
                if (this.validateProduct()){
                    return;
                }

                let me = this;

                axios.put('/producto/actualizar',{
                    'category_id': this.category_id,
                    'id': this.product_id,
                    'code': this.code,
                    'name': this.name,
                    'price': this.price,
                    'stock': this.stock,
                    'description': this.description
                }).then(function (response){
                    me.closeModal();
                    me.lists_products(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },
            enableProduct(id){

                const swalWithBootstrapButtons = Swal.mixin({
                  confirmButtonClass: 'btn btn-success',
                  cancelButtonClass: 'btn btn-danger',
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                  title: 'Esta seguro que desea Activar el Producto?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, Activar!',
                  cancelButtonText: 'No, cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {
                        console.log(id);

                        let me = this;

                        axios.put('/producto/activar',{
                            'id': id
                        }).then(function (response){
                            me.lists_products(1,'','nombre');
                            swalWithBootstrapButtons(
                              'Activada!',
                              'El Producto fue Activado..!',
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
            disableProduct(id){

                const swalWithBootstrapButtons = Swal.mixin({
                  confirmButtonClass: 'btn btn-success',
                  cancelButtonClass: 'btn btn-danger',
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                  title: 'Esta seguro que desea desactivar Producto?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, desactivar!',
                  cancelButtonText: 'No, cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                        let me = this;

                        axios.put('/producto/desactivar',{
                            'id': id
                        }).then(function (response){
                            me.lists_products(1,'','nombre');

                            swalWithBootstrapButtons(
                              'Desactivado!',
                              'El Producto fue Desactivado..!',
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
            validateProduct(){
                this.errorCategory = 0;
                this.errorShowProduct = [];

                if (!this.category_id) this.errorShowProduct.push("El campo Categoría no puede estar vacio.");
                if (!this.name) this.errorShowProduct.push("El nombre del Producto no puede estar vacio.");
                if (!this.price) this.errorShowProduct.push("El campo Precio no puede estar vacio.");
                if (!this.stock) this.errorShowProduct.push("El campo Stock no puede estar vacio.");

                if(this.errorShowProduct.length) this.errorProduct = 1;

                return this.errorProducto;
            },
    		closeModal(){
    			this.modal=0;
    			this.titleModal = '';
                this.category_id = 0,
                this.category_name = '';
                this.code = '';
    			this.name = '';
                this.price = 0;
                this.stock = 0;
    			this.description = '';
                this.errorProduct = '';
    		},	
    		openModal(modelo, action, data = []){
    			switch(modelo){
    				case 'product':
    				{
    					switch(action){
    						case 'store':
    						{
    							this.modal = 1;
		    					this.titleModal = 'Registrar Producto';
                                this.category_id = 0,
                                this.category_name = '';
                                this.code = '';
                                this.name = '';
                                this.price = 0;
                                this.stock = 0;
                                this.description = '';
                                this.typeAction = 1;
                                this.errorProduct= 0;
                                this.errorShowProduct = [];
		    					break;
    						}
    						case 'update':
    						{
                                // console.log(data);
                                this.modal = 1;
                                this.titleModal = 'Actualizar Producto';
                                this.typeAction = 2;
                                this.product_id = data['id'];
                                this.category_id = data['category_id'];
                                this.code = data['code'];
                                this.name = data['name'];
                                this.stock = data['stock'];
                                this.price = data['price'];
                                this.description = data['description'];
                                this.errorProduct= 0;
                                this.errorShowProduct = [];
		    					break;	
    						}
    					}
    					this.selectCategory();
    				}
    			}
    		}
    	},
        mounted() {
            this.lists_products(1,this.search,this.criteria);
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
</style>		
