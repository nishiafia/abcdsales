<template>
		<div class="container">
				<div class="row mt-5">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Branch List</h3>
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
												<th>Manager</th>
												<th>Company</th>
												<th>Status</th>
												<th>Registered At</th>
												<th>Modify</th>
									</tr> 
									<tr v-for="branchdata in branchlist.data" :key="branchdata.id">
										<td>{{ branchdata.branchname }}</td>
										<td>{{ branchdata.address }}</td>
										<td>{{ branchdata.phone}}</td>
										<td>{{ branchdata.contact_person.name}}</td>
										<td>{{ branchdata.supervisor.name}}</td>
										<td v-if="branchdata.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
										<td>{{ branchdata.created_at | formatDate}}</td>
										<td>
												<a href="/#/branch" title="Edit" data-id="branchdata.id" @click="editModalWindow(branchdata)">
														<i class="fa fa-edit blue"></i>
												</a>
												|
												<a href="/#/branch" title="Delete" @click="deleteBranch(branchdata.id)">
														<i class="fa fa-trash red"></i>
												</a>
										</td>
									</tr>
								</tbody></table>
							 <pagination :data="branchlist" @pagination-change-page="loadBranches"></pagination>
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
										<h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Branch</h5>
										<h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Branch</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
								</div>

						<form @submit.prevent="editMode ? updateBranch() : createBranch()">
							<div class="modal-body">
								<div class="form-group">
									<input v-model="form.branchname" type="text" name="branchname"
									placeholder="Branch Name"
									class="form-control" :class="{ 'is-invalid': form.errors.has('branchname') }">
									<has-error :form="form" field="branchname"></has-error>
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
									<select name="branch_supervisor" v-model="form.branch_supervisor" id="branch_supervisor" class="form-control" :class="{ 'is-invalid': form.errors.has('branch_supervisor') }">
									<option value="">Select Company</option> 
									<option v-for="company in companies" v-bind:value="company.id" :key="company.id">
									{{ company.name }}
									</option>

									</select>
									<has-error :form="form" field="branch_supervisor"></has-error>
								</div>
								<div class="form-group">
									<select name="branch_contact_person" v-model="form.branch_contact_person" id="branch_contact_person" class="form-control" :class="{ 'is-invalid': form.errors.has('branch_contact_person') }">
									<option value="">Select Manager</option>
									<option v-for="contactperson in contactpersons" v-bind:value="contactperson.id" :key="contactperson.id">
									{{ contactperson.name }}
									</option>
									</select>
									<has-error :form="form" field="branch_contact_person"></has-error>
								</div>
								<div class="form-group">
									<select name="isactive" v-model="form.isactive" id="isactive" class="form-control" :class="{ 'is-invalid': form.errors.has('isactive') }">
									<option value="">Select Status</option>
									<option value="1">Active</option>
									<option value="0">Inactive</option>
									</select>
									<has-error :form="form" field="isactive"></has-error>
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
								branchlist: {},
								companies: {},
								contactpersons: {},
								//shouldDisable: false,
								form: new Form({
										id: '',
										branchname : '',
										address: '',
										phone: '',
										branch_supervisor: '',
										branch_contact_person: '',
										isactive: '',

								})
						}
				},
				methods: {

				editModalWindow(branchdata){
					 this.form.clear();
					 this.editMode = true
						//this.shouldDisable =true
					 this.form.reset();
					 $('#addNew').modal('show');
					 this.form.fill(branchdata)
					 
				},
				updateBranch(){
					 this.form.put('/branch/'+this.form.id)
							 .then(()=>{

									 Toast.fire({
											icon: 'success',
											title: 'Branch updated successfully'
										})

										Fire.$emit('AfterCreatedBranchLoadIt');

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

				loadBranches(page) {
					if (typeof page === 'undefined') {
						page = 1;
					}
					axios.get('/branch?page=' + page)
						.then( data =>{
							console.log("data =>", data);
							this.branchlist = data.data
						});
					console.log("data11111",this.branchlist);
					//debugger;
					//alert("adasds",data);
				},
				loadCompany() {
				axios.get('/getcompany').then( data => (this.companies = data.data));
						//console.log("company:",this.companies);

				},
				loadContactperson() {
				 axios.get('/getbranchcontactperson').then( data => (this.contactpersons = data.data));
						//console.log("person:",this.contactpersons);

				},

				createBranch(){

						this.$Progress.start()

						this.form.post('/branch')
								.then((response) => {
										console.log("response:",response.data);
										if(response.data === '')
										{    // alert("Wrong");
													Fire.$emit('AfterCreatedBranchLoadIt'); //custom events
												 Toast.fire({
													icon: 'error',
													title: 'Branch Already Exists!!'
												})
										}
										else{
										Fire.$emit('AfterCreatedBranchLoadIt'); //custom events

												Toast.fire({
													icon: 'success',
													title: 'Branch created successfully'
												})

												this.$Progress.finish()

												$('#addNew').modal('hide');

								}})
								.catch(() => {
									 console.log("Error......")
								})



						//this.loadUsers();
					},
					deleteBranch(id) {
						Swal.fire({
							title: 'Are you sure?',
							text: "You won't be able to revert this!",
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, Inactive it!'
						}).then((result) => {

							if (result.value) {
								//Send Request to server
								this.form.delete('/branch/'+id)
										.then((response)=> {
														Swal.fire(
															'Inactive!',
															'Branch Inactive successfully',
															'success'
														)
										this.loadBranches();

										}).catch(() => {
												Swal.fire({
													icon: 'error',
													title: 'Oops...',
													text: 'Something went wrong!',
													footer: '<a href>Why do I have this issue?</a>'
												})
										})
								}

						})
					}
				},
			
				created() { //Like Mounted this method
					this.loadCompany();
					 
					 this.loadContactperson();
						this.loadBranches();

						Fire.$on('AfterCreatedBranchLoadIt',()=>{ //custom events fire on
								this.loadBranches();
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