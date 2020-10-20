<template>
		<div class="container">
				<div class="row mt-5">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Customer List</h3>
								<div class="card-tools">
										<button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New <i class="fa fa-user-plus" aria-hidden="true"></i></button>
								</div>
							</div>
							<div class="card-body table-responsive p-0">
								<table class="table table-hover">
									<tbody>
										<tr>
												<th>Name</th>
												<th>Address</th>
												<th>Phone</th>
												<th>Others</th>
												<th>Registered At</th>
												<th>Modify</th>
									</tr> 
									<tr v-for="customerdata in customerlist.data" :key="customerdata.id">
										<td>{{ customerdata.name }}</td>
										<td>{{ customerdata.address }}</td>
										<td>{{ customerdata.phone}}</td>
										<td>{{ customerdata.others}}</td>
										<td>{{ customerdata.created_at | formatDate}}</td>
										<td>
												<a href="/#/customer" title="Edit" data-id="customerdata.id" @click="editModalWindow(customerdata)">
														<i class="fa fa-edit blue"></i>
												</a>
												|
                                                <router-link :to="'/customerorderlist/' + customerdata.id"  title="Order List">
                                                    <i class="fa fa-sort" aria-hidden="true"></i>
                                                </router-link>
										</td>
									</tr>
								</tbody></table>
							 <pagination :data="customerlist" @pagination-change-page="loadBranches"></pagination>
							</div>
							<div class="card-footer">
							</div>
						</div>

					</div>
				</div>
						<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
								<div class="modal-header">

										<h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Customer</h5>
										<h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Customer</h5>

										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
								</div>

                        <form @submit.prevent="editMode ? updateCustomer() : createCustomer()">
                            <div class="modal-body">
                                <div class="form-group">
                                    <input v-model="form.name" type="text" name="name"
                                    placeholder="Name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>

                                <div class="form-group">
                                    <textarea v-model="form.address" placeholder="Enter Address" name="address" class="form-control"  :class="{ 'is-invalid': form.errors.has('address') }" ></textarea>
                                    <has-error :form="form" field="address"></has-error>
                                </div>

                                <div class="form-group" >
                                    <input v-model="form.phone" type="number" name="phone" id="phone" placeholder="Enter Phone"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                    <has-error :form="form" field="phone"></has-error>
                                </div>

                                <div class="form-group">
                                    <textarea v-model="form.others" placeholder="Others" name="others" class="form-control"  :class="{ 'is-invalid': form.errors.has('others') }" ></textarea>
                                    <has-error :form="form" field="others"></has-error>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
                                <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>

</template>

<script>
		export default {
				data() {
						return {
								editMode: false,
								customerlist: {},
								shouldDisable: false,
								form: new Form({
										id: '',
										name : '',
										address: '',
										phone: '',
										others: '',
								})
						}
				},
				methods: {

				editModalWindow(customerdata){
					 this.form.clear();
					 this.editMode = true
					 this.shouldDisable =true
					 this.form.reset();
					 $('#addNew').modal('show');
					 this.form.fill(customerdata)
					 
				},
				updateCustomer(){
					 this.form.put('api/customer/'+this.form.id)
							 .then(()=>{
									 Toast.fire({
											icon: 'success',
											title: 'Customer updated successfully'
										})
										Fire.$emit('AfterCreatedCustomerLoadIt');

										$('#addNew').modal('hide');
							 })
							 .catch(()=>{
									console.log("Error.....")
							 })
				},
				openModalWindow(){
					 this.editMode = false
					// this.shouldDisable =false
					 this.form.reset();
					 $('#addNew').modal('show');
				},

				loadCustomers(page) {
					if (typeof page === 'undefined') {
						page = 1;
					}
					axios.get('api/customer?page=' + page)
						.then( data =>{
							console.log("data =>", data);
							this.customerlist = data.data
						});
				},
				createCustomer(){
						this.$Progress.start()
						this.form.post('api/customer')
								.then((response) => {
										console.log("response:",response.data);
										Fire.$emit('AfterCreatedCustomerLoadIt'); //custom events
												Toast.fire({
													icon: 'success',
													title: 'Customer created successfully'
												})
												this.$Progress.finish()
												$('#addNew').modal('hide');
								})
								.catch(() => {
									 console.log("Error......")
								})
					},
				},
				created() { //Like Mounted this method
						this.loadCustomers();
						Fire.$on('AfterCreatedCustomerLoadIt',()=>{ //custom events fire on
								this.loadCustomers();
						});
				}
		}
</script> 
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}
.pagination{
	margin:15px;
}
</style>