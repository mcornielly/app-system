<template>
<main class="main">
	<!-- Breadcrumb -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="/">Escritorio</a></li>
	</ol>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				Dashboard
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="card card-chart">
							<div class="card-header">
								<h4>Ingresos</h4>
							</div>
							<div class="card-content">
								<div class="ct-chart">
									<canvas id="incomeChart">		
									</canvas>								
								</div>				
							</div>
							<div class="card-footer">
								<p>Compra de los ultimos meses</p>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card card-chart">
							<div class="card-header">
								<h4>Ventas</h4>
							</div>
							<div class="card-content">
								<div class="ct-chart">
									<canvas id="saleChart">		
									</canvas>								
								</div>				
							</div>
							<div class="card-footer">
								<p>Ventas de los ultimos meses</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>	
</template>
<script>
import Chart from 'chart.js';

export default {
	data(){
		return {
			varIncome: null,
			chartIncome: null,
			incomes: [],
			varTotalIncome: [],
			varMonthIncome: [],

			varSales: null,
			chartSales: null,
			sales: [],
			varTotalSale: [],
			varMonthSale: [],
		}	
	},
	methods: {
		getIncomes(){
			let me = this;
			var url = '/dashboard';
			axios.get(url).then(function (response){
				var result = response.data;
				me.incomes = result.incomes;
				//cargamos los datos del chart
				me.loadIncomes();
			})
			.catch(function (error){
				console.log(error);
			});
		},
		getSales(){
			let me = this;
			var url = '/dashboard';
			axios.get(url).then(function (response){
				var result = response.data;
				me.sales = result.sales;
				//cargamos los datos del chart
				me.loadSales();
			})
			.catch(function (error){
				console.log(error);
			});
		},

		loadIncomes(){
			let me = this;
			me.incomes.map(function(x){
				me.varMonthIncome.push(x.month);
				me.varTotalIncome.push(x.total);
			});
			me.varIncome = document.getElementById("incomeChart").getContext('2d');

		 	me.chartIncome = new Chart(me.varIncome, {
		    type: 'bar',
		    data: {
		        labels: me.varMonthIncome,
		        datasets: [{
		            label: 'Ingresos',
		            data: me.varTotalIncome,
		            backgroundColor: 'rgba(255, 99, 132, 0.2)',
		            borderColor: 'rgba(255,99,132,1)',
		            borderWidth: 1
			        }]
			    },
			    options: {
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
		},
		
		loadSales(){
			let me = this;
			me.sales.map(function(x){
				me.varMonthSale.push(x.month);
				me.varTotalSale.push(x.total);
			});
			me.varSales = document.getElementById("saleChart").getContext('2d');

		 	me.chartSales = new Chart(me.varSales, {
		    type: 'bar',
		    data: {
		        labels: me.varMonthSale,
		        datasets: [{
		            label: 'Ventas',
		            data: me.varTotalSale,
		            backgroundColor: 'rgba(54, 162, 235, 0.2)',
		            borderColor: 'rgba(54, 162, 235, 1)',
		            borderWidth: 1
			        }]
			    },
			    options: {
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
		}
	},
	mounted(){
		this.getIncomes();
		this.getSales();
	}
}
	
</script>