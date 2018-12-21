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
                        <i class="fa fa-align-justify"></i> Roles
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                      <option value="name">Nombre</option>
                                      <option value="description">Descripción</option>
                                    </select>
                                    <input type="text" class="form-control" v-model="search" placeholder="Texto a buscar" @keyup.enter="lists_roles(1,search,criteria)">
                                    <button type="submit" class="btn btn-primary" @click="lists_roles(1,search,criteria)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="role in roles" :key="role.id">
                                    <td v-text="role.name"></td>
                                    <td v-text="role.description"></td>
                                    <td>
                                        <div v-if="role.condition">
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
        </main>
</template>

<script>
    export default {
    	data() {
    		return{
                role_id: 0,
    			name: '',
    			description: '',
    			roles: [],
    			modal: 0,
    			titleModal: '',
    			typeAction: 0,
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
    		lists_roles(page,search,criteria){
    			let me = this;
                var url = '/roles?page=' + page + '&search='+ search + '&criteria='+ criteria;
    			axios.get(url).then(function (response){

                    var result = response.data;

    				me.roles = result.roles.data;
                    me.pagination = result.pagination;

    			}).catch(function (error){
    				console.log(error);
    			});
    		},
            nextPage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                me.lists_roles(page,search,criteria);
            }
    	},
        mounted() {
            this.lists_roles(1,this.search,this.criteria);
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