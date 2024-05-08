<?php
	require_once 'helpers/ultil.php';
	$restaurant  = new Restaurant();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>
	<div id="app" class="container">
		<div class="main">
			<div class="section-step" step="{{step}}">
				<ul>
					<li v-bind:class="step == 1 ? 'active' : ''" v-on:click="moveToStep(1)">Step 1</li>
					<li v-bind:class="step == 2 ? 'active' : ''" v-on:click="moveToStep(2)">Step 2</li>
					<li v-bind:class="step == 3 ? 'active' : ''" v-on:click="moveToStep(3)">Step 3</li>
					<li v-bind:class="step == 4 ? 'active' : ''" v-on:click="moveToStep(4)">Review</li>
				</ul>
			</div>
			<div class="form-1" v-show="step == 1">
				<div class="row-field">
					<div class="meal">
						<label for="">Please select a meal</label>
						<select v-on:change="changeMeal" v-model="meal">
							<option v-for="(m,mIndex) in meals" :value="m">{{m}}</option>
						</select>
					</div>
					<div class="">
						<label for="">Please Enter number of people</label>
						<input type="number" v-model="number_people">
					</div>
				</div>
				<button type="button" v-on:click="nextStep">Next</button>
			</div>
			<div class="form-2" v-show="step == 2">
				<div class="row-field">
					<div class="restaurant">
						<label for="">Please select a restaurant</label>
						<select v-model="restaurant" v-on:change="changeMeal">
							<option v-for="(r,rIndex) in restaurants" :value="r">{{r}}</option>
						</select>
					</div>
					
				</div>
				<button type="button" v-on:click="prevStep">Previous</button>
				<button type="button" v-on:click="nextStep">Next</button>
			</div>
			<div class="form-3" v-show="step == 3">
				<div class="row-field row-flex" v-for="(ds,dsIndex) in dish">
					<div class="meal">
						<label for="">Please select a Dish</label>
						<select  v-model="ds.name">
							<option value="">-----</option>
							<option v-for="(d,dIndex) in allow_dish" :value="d.name">{{d.name}}</option>
						</select>
					</div>
					<div class="">
						<label for="">Please Enter no of servings</label>
						<input type="number" v-model="ds.no_of_serving">
					</div>
					<div v-show="dish.length > 1"><span v-on:click="removeDish" class="remove-dish">remove</span></div>
				</div>
				<div class="more_dish">
					<span v-on:click="moreDish">+</span>
				</div>

				<button type="button" v-on:click="prevStep">Previous</button>
				<button type="button" v-on:click="nextStep">Next</button>
			</div>
			<div class="form-4" v-show="step == 4">
				<div class="table_result_dish">
					<table>
						<tbody>
							<tr>
								<td>Mead</td>
								<td>{{meal}}</td>
							</tr>
							<tr>
								<td>No of People</td>
								<td>{{number_people}}</td>
							</tr>
							<tr>
								<td>Restaurant</td>
								<td>{{restaurant}}</td>
							</tr>
							<tr>
								<td>Dish</td>
								<td>
									<div class="reult_dish">
										<p v-for="(ds,dsIndex) in dish" v-show="ds.name !== ''">{{ds.name}} - {{ds.no_of_serving}}</p>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<button type="button" v-on:click="prevStep">Previous</button>
				<button type="button">Submit</button>
			</div>
		</div>
	</div>
	<script>
	  	const app = Vue.createApp({
	    	data() {
	      		return {
	      			step : 1,
	      			meal : 'lunch',
	      			number_people : 1,
	        		meals: <?php echo json_encode($restaurant->meals); ?>,
	        		restaurants: [],
	        		restaurant: '',
	        		allow_dish : [],
	        		dish : [{name : '' , no_of_serving : 1}],
	        		dishes: <?php echo json_encode($restaurant->dishs); ?>
	      		}
	    	},
	    	mounted : function(){
	    		this.changeMeal();
	    	},
	    	methods: {
		      	nextStep() {
		        	this.step++;
		      	},
		      	prevStep() {
		        	this.step--;
		      	},
		      	moveToStep(s){
		      		this.step = s;
		      	},
		      	changeMeal(){
		      		this.restaurants = [];
		      		this.dishes.forEach(dish => {
		      			if(dish.availableMeals.includes(this.meal) && !this.restaurants.includes(dish.restaurant)) this.restaurants.push(dish.restaurant);
		      		});
		      		if(this.restaurants.length > 0 && !this.restaurants.includes(this.restaurant)) this.restaurant = this.restaurants[0];
		      		this.allow_dish = this.getAlowDish();
		      		this.getDish();
		      	},
		      	getAlowDish(){
		      		var datas = this.dishes;
		      		if(datas.length == 0) return [];
		      		var results = datas.filter(data => data.availableMeals.includes(this.meal) && data.restaurant == this.restaurant);
		      		return results;
		      	},
		      	filterDish(dish){
		      		var datas = this.allow_dish.filter(d => d.name == dish.name);;
		      		if(datas.length == 0) return false;
		      		var results = datas.filter(data => data.availableMeals.includes(this.meal) && data.restaurant == this.restaurant);
		      		return results.length > 0;
		      	},
		      	getDish(){
		      		this.dish.filter(dish => dish.name === '' || this.filterDish(dish));
		      	},
		      	moreDish(){
		      		this.dish.push({name : '' , no_of_serving : 1});
		      	},
		      	removeDish(id){
		      		this.dish.splice(id,1);
		      	}

		    }
	  	})
	 	app.mount('#app')

	</script>
</body>
</html>
