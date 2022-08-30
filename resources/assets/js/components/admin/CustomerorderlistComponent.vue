<template>
		<div class="container">
				<div class="row mt-5">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Order List of {{ customerid }}</h3>
							</div>
							<div class="card-body table-responsive p-0">
								<table class="table table-hover">
									<tbody>
										<tr>
												<th>Order#</th>
												<th>G.Code</th>
												<th>G.Title</th>
												<th>P.Title</th>
												<th>Qty</th>
                                                <th>Sell Price</th>
												<th>Status</th>
												<th>Date</th>
												<th>Modify</th>
									</tr> 
									<tr v-for="branchdata in branchlist.data" :key="branchdata.id">
										<td>{{ branchdata.branchname }}</td>
										<td>{{ branchdata.address }}</td>
										<td>{{ branchdata.phone}}</td>
										<td>{{ branchdata.contact_person.name}}</td>
										<td>{{ branchdata.supervisor.name}}</td>
                                        <td>{{ branchdata.supervisor.name}}</td>
										<td v-if="branchdata.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
										<td>{{ branchdata.created_at | formatDate}}</td>
										<td>
												<a href="/#/branch" title="Edit" data-id="branchdata.id" @click="editModalWindow(branchdata)">
														<i class="fa fa-edit blue"></i>
												</a>
												|
                                                <router-link to="/orderlist"  title="Order List">
                                                    <i class="fa fa-sort" aria-hidden="true"></i>
                                                </router-link>
												 

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


						
		</div>

</template>

<script>
		export default {
              props: ['id'],
				data() {
						return {
								editMode: false,
								branchlist: {},
								companies: {},
								contactpersons: {},
								customerid: this.id,
	
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
							//console.log("data =>", data);
							this.branchlist = data.data
						});
					//console.log("data11111",this.branchlist);
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
										//console.log("response:",response.data);
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
                        this.loadBranches();
                         console.log('created');
                      console.log(this.customerid);
                      
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