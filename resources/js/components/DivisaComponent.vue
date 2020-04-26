<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Divisa</li>
            </ol>
            <div class="container-fluid">

                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Divisa
                        <button type="button" class="btn btn-secondary float-sm-right btn-movil" @click="openModal('divisa', 'store')" data-toggle="modal" data-target="#modalDivisa">
                            <i class="icon-plus"></i>&nbsp;Actualizar
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                      <option value="created_at">Fecha Publicación</option>
                                      <option value="price">Cotización</option>
                                      <option value="num_document">Usuario</option>
                                    </select>
                                    <input type="text" class="form-control" v-model="search" placeholder="Texto a buscar" @keyup.enter="lists_divisa(1,search,criteria)">
                                    <button type="submit" class="btn btn-primary" @click="lists_divisa(1,search,criteria)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>#</th>
                                        <th>Fecha Publicación</th>
                                        <th>Cotización</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="div in divisa" :key="div.id">
                                        <td>
                                            <button type="button" @click="openModal('divisa', 'update', div)" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalDivisa">
                                              <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                            <!-- <button type="button" class="btn btn-danger btn-sm" @click="disableUser(div.id)">
                                                <i class="icon-trash"></i>
                                            </button> -->
                                        </td>
                                        <td v-text="div.id"></td>
                                        <td v-text="div.created_at"></td>
                                        <td v-text="div.price"></td>
                                        <td v-text="div.user_name"></td>
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
            <div id="modalDivisa" class="modal fade" tabindex="-1" :class="{'show' : modal}" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="titleModal"></h4>
                            <button type="button" class="close" @click="closeModal()" aria-label="Close" data-dismiss="modal">
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
                                    <label class="col-md-3 form-control-label">Cotización de la Divisa</label>
                                    <div class="col-md-9">
                                        <input type="numer" class="form-control" placeholder="Ingrese la cotización del momento" v-model="divisa">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==1" @click="createDivisa()" data-dismiss="modal">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==2" @click="updateDivisa()" data-dismiss="modal">Actualizar</button>
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
                price: 0.00,
                name: '',
                divisa: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,
                criteria: 'price',
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
    		lists_divisa(page,search,criteria){
    			let me = this;
                var url = '/divisa?page=' + page + '&search='+ search + '&criteria='+ criteria;
    			axios.get(url).then(function (response){
                    console.log(response)

                    var result = response.data;
                    me.divisa = result.divisa.data;
                    me.pagination = result.pagination;

    			}).catch(function (error){
    				console.log(error);
    			});
    		}
    	},
        mounted() {
            this.lists_divisa(1,this.search,this.criteria);
        }
    }
</script>

<style>
/* 	.modal-content{
		width: 100% !important;
		position: adsolute !important;
        margin-top: 15em; 
	}

	.show_{
		display: list-item !important;
		opacity: 1 !important;
		position: adsolute !important;
		background-color: #3c29297a !important;
	} */

    @media screen and (min-width: 400px) {
        .btn-movil {
            float: right;
        }
    }
</style>		