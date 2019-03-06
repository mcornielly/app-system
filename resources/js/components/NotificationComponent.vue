<template>
	<li class="nav-item d-md-down-none">
	    <a class="nav-link" href="#" data-toggle="dropdown">
	        <i class="icon-bell"></i>
	        <span class="badge badge-pill badge-danger">{{ notifications.length }}</span>
	    </a>
	    <div class="dropdown-menu dropdown-menu-right">
	        <div class="dropdown-header text-center">
	            <strong>Notificaciones</strong>
	        </div>
			<div v-if="notifications.length">
				<li v-for="item in listar" :key="item.id">
					<a class="dropdown-item" href="#">
					    <i class="fa fa-envelope-o"></i> {{ item.incomes.msj }}
					    <span class="badge badge-success">{{ item.incomes.num }}</span>
					</a>
					<a class="dropdown-item" href="#">
					    <i class="fa fa-tasks"></i> {{ item.sales.msj }}
					    <span class="badge badge-danger">{{ item.sales.num }}</span>
					</a>
				</li>
			</div>
			<div v-else>
				<a href=""><span>No hay notificaciones</span></a>
			</div>
	    </div>
	</li>
</template>

<script>
export default {
	props : ['notifications'],
	data (){
		return {
			arrayNotifications: []
		}
	},
	computed: {
		listar: function(){
			//return this.notifications[0];
			this.arrayNotifications = Object.values(this.notifications[0]);
			if (this.notifications == '') {
				return this.arrayNotifications = [];
			}else{
				//Capturo la ultima notificación agregada.
				this.arrayNotifications = Object.values(this.notifications[0]);
				//Se valida el indice fuera del rango
				if(this.arrayNotifications.length > 3){
					//Si el número es mayor 3 Es cuando la notificaciones son obtenias por el canal de  axios
					return Object.values(this.arrayNotifications[4]);
				}else{
					//Si el número es menor 3 Es cuando la notificaciones son obtenias por canal privado - Larave echo / Pusher.
					return Object.values(this.arrayNotifications[0]);
				}	
			}
		}
	}
}	
</script>