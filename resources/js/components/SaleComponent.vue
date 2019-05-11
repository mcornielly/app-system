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
                        <button type="button" class="btn btn-secondary" @click="showFormSale()">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
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
                                                <template v-if="sale.state=='Registrado'">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="disableSale(sale.id)" title="Anular">
                                                      <i class="icon-trash"></i>
                                                    </button>
                                                </template>    
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
                    <!-- Formulario de Detalle de Ventas -->
                    <template v-else-if="list==0">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                       <label for="">Cliente(*)</label>
                                       <v-select
                                            label="name"
                                            placeholder="Buscar Clientes..."
                                            :on-search="selectClients"
                                            :options="clients"
                                            :onChange="getDataClients"></v-select>                                     
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Producto <span style="color:red;" v-show="product_id==0">(*) Seleccione</span></label>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" v-model="code" @keyup.enter="searchProduct()"placeholder="Ingresar producto">
                                            <button class="btn btn-primary" @click="openModal()">...</button>
                                            <input type="text" readonly="true" class="form-control" v-model="product_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Precio <span style="color:red;" v-show="price==0">(*) Ingrese</span></label>
                                        <input type="number" step="any" class="form-control" v-model="price">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Cantidad <span style="color:red;" v-show="quantity==0">(*) Ingrese</span></label>
                                        <input type="number" class="form-control" v-model="quantity">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Descuento</span></label>
                                        <input type="number" class="form-control" v-model="discount">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-comtrol">
                                        <button @click="addProducts()" class="btn btn-success form-control btnadd">
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
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Descuento(%)</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="detail_sales.length">
                                            <tr v-for="(detail, index) in detail_sales" :key="detail.id">
                                                <td>
                                                    <button @click="deleteProduct(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detail.product_name">
                                                </td>
                                                <td>
                                                    <input v-model="detail.price" type="number" value="3" class="form-control">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-if="detail.quantity>detail.stock">Stock: {{ detail.stock }}</span>
                                                    <input v-model="detail.quantity" type="number" value="2" class="form-control">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-if="detail.discount>(detail.price*detail.quantity)">Descuento no ermitido</span>
                                                    <input v-model="detail.discount" type="number" value="2" class="form-control">
                                                </td>
                                                <td v-if="detail.discount==0">
                                                    {{ (detail.price*detail.quantity-detail.discount).toFixed(2) }}
                                                </td>
                                                <td v-else>
                                                    {{ (detail.price*detail.quantity-calculateDiscount).toFixed(2) }}
                                                </td>
                                            </tr>

                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" class="text-right"><strong>Total Parcial</strong></td>
                                                <td>$ {{ total_partial = (total - total_tax).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" class="text-right"><strong>Total Impuesto</strong></td>
                                                <td>$ {{ total_tax = ((total*tax)).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" class="text-right"><strong>Total Neto</strong></td>
                                                <td>$ {{ total=calculateTotal }}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
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
                                   <button type="button" class="btn btn-primary" @click="createSale()">Registrar Venta</button>
                               </div>
                           </div>
                        </div>
                    </template>
                    <!-- Fin Formulario de Detalle de Ventas -->
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
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criteria_p">
                                          <option value="name">Nombre</option>
                                          <option value="description">Descripción</option>
                                          <option value="code">Código</option>
                                        </select>
                                        <input type="text" class="form-control" v-model="search_p" placeholder="Texto a buscar" @keyup.enter="lists_products(search_p,criteria_p)">
                                        <button t_pe="submit" class="btn btn-primary" @click="lists_products(search_p,criteria_p)"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in products" :key="product.id">
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" @click="addProductsModal(product)">
                                              <i class="icon-check"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="product.code"></td>
                                        <td v-text="product.name"></td>
                                        <td v-text="product.category_name"></td>
                                        <td v-text="product.price"></td>
                                        <td v-text="product.stock"></td>
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
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
<!--                             <button type="button" class="btn btn-primary" v-if="typeAction==1" 
                            @click="createClient()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="typeAction==2" @click="updateClient()">Actualizar</button> -->
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
            selectClients(search, loading){
                let me = this;
                loading(true)

                var url = '/cliente/seleccionar-cliente?filter='+search;
                axios.get(url).then(function (response){

                    var result = response.data;
                    q: search
                    me.clients = result.clients;
                    loading(false)


                }).catch(function (error){
                    console.log(error);
                });
            },
            getDataClients(val1){
                let me = this;
                me.loading = true;
                me.client_id = val1.id;
            },
            searchProduct(){
                let me = this;
                var url = '/producto/buscar-producto-venta?filter='+me.code;

                axios.get(url).then(function(response){
                    var result = response.data;
                    me.product = result.product;

                    if(me.product.length>0){
                        me.product_name = me.product[0]['name'];
                        me.product_id = me.product[0]['id'];
                        me.price = me.product[0]['price'];
                        me.stock = me.product[0]['stock'];
                    }else{
                        me.product_name = "No existe producto";
                        me.product_id = 0;
                    }
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            pdfSale(id){
                window.open('/venta/pdf/' + id + ',' + '_blank');
            },
            findProduct(id){
                var sw=0;

                for(var i=0; i<this.detail_sales.length; i++){
                    if(this.detail_sales[i].product_id==id){
                        sw=true;
                    }
                }
                return sw;
            },
            addProducts(){
                let me = this;

                if(me.product_id==0 || me.quantity==0 || me.price==0){

                }
                else{
                    if(me.findProduct(me.product_id)){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Este articulo se encuentra registrado..!'
                        })
                    }else{
                        if(me.findProduct(me.quantity>me.stock)){
                            swal({
                                type: 'error',
                                title: 'Error...',
                                text: 'La cantidad no esta disponible..!'
                            })
                        }else{
                            me.detail_sales.push({
                                product_id: me.product_id,
                                product_name: me.product_name,
                                quantity: me.quantity,
                                price: me.price,
                                discount: me.discount,
                                stock: me.stock
                            });
                            me.code = '';
                            me.product_name = '';
                            me.product_id = 0;
                            me.product_id = '';
                            me.quantity = 0;
                            me.price = 0;
                            me.discount = 0;
                            me.stock = 0;
                        }
                    }
                }
            },
            addProductsModal(data = []){
                let me = this;

                if(me.findProduct(data['id'])){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Este producto se encuentra registrado..!'
                        })
                }else{
                    me.detail_sales.push({
                        product_id: data['id'],
                        product_name: data['name'],
                        quantity: 1,
                        price: data['price'],
                        discount: 0,
                        stock: data['stock']

                    });
                }
            },
            lists_products(search,criteria){
                let me = this;
                var url = '/producto/listar-productos-venta?search='+ search + '&criteria='+ criteria;
                axios.get(url).then(function (response){

                    var result = response.data;
                    me.products = result.products.data;

                }).catch(function (error){
                    console.log(error);
                });
            },
            deleteProduct(index){
                let me = this;
                me.detail_sales.splice(index,1);
            },
            nextPage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                me.lists_sale(page,search,criteria);
            },
    		createSale(){
                if (this.validateSale()){
                    return;
                }

    			let me = this;

                axios.post('ventas',{
                    'client_id': this.client_id,
                    'type_voucher': this.type_voucher,
                    'num_voucher': this.num_voucher,
                    'serie_voucher': this.serie_voucher,
                    'tax': this.tax,
                    'total': this.total,
                    'data': this.detail_sales

                }).then(function (response){
    				me.list = 1;
    				me.lists_sale(1,'','num_voucher');
                    me.client_id = 0;
                    me.type_voucher = 'Boleta';
                    me.serie_voucher = '';
                    me.num_voucher = '';
                    me.tax = 0.16;
                    me.total = 0;
                    me.product_id = 0;
                    me.producto = '';
                    me.quantity = 0;
                    me.price = 0;
                    me.stock = 0;
                    me.code = '';   
                    me.detail_sales = [];
                    window.open('/venta/pdf/' + response.data.id + ',' + '_blank');

    			}).catch(function (error){
    				console.log(error);
                });
            },
            validateSale(){
                let me=this;
                me.errorSale = 0;
                me.errorShowSale = [];
                var prod_error;

                me.detail_sales.map(function(x){
                    if(x.quantity>x.stock){
                        prod_error = x.product_name + " con stock insuficiente.";
                        me.errorShowSale.push(prod_error);    
                    }
                });

                if(me.client_id==0) me.errorShowSale.push("Seleccione un Cliente");
                if(me.type_document==0) me.errorShowSale.push("Seleccione el Comprobante");
                if(!me.num_voucher) me.errorShowSale.push("Ingrese el número de Comprobante");
                if(!me.tax) me.errorShowSale.push("Ingrese el Impuesto de Compra");
                if(me.detail_sales.length<=0) me.errorShowSale.push("Ingrese Detalle");

                if(me.errorShowSale.length) me.errorSale = 1;

                return me.errorSale;
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
            },
            disableSale(id){

                const swalWithBootstrapButtons = Swal.mixin({
                  confirmButtonClass: 'btn btn-success',
                  cancelButtonClass: 'btn btn-danger',
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                  title: 'Esta seguro que desea anular esta venta?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, Anular!',
                  cancelButtonText: 'No, Cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                        let me = this;

                        axios.put('/venta/desactivar',{
                            'id': id
                        }).then(function (response){
                            me.lists_sale(1,'','num_voucher');

                            swalWithBootstrapButtons(
                              'Desactivado!',
                              'La Venta fue anulada con éxito}..!',
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
    		closeModal(){;
    			this.modal=0;
                this.titleModal='';
    		},	
    		openModal(){
                this.search_p = '';
                this.products = [];
                this.modal = 1;
				this.titleModal = 'Seleccione uno o varios productos';
    		}
    	},
        mounted() {
            this.lists_sale(1,this.search,this.criteria);
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