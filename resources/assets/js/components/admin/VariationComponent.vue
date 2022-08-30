<template>
    <div class="container">
        <div class="row mt-5">
           <div class="scom"> 
              <table class="switchcompany">
                <tbody>
                  <tr>
                    <td class="switchlabel">Switch Company:</td>
                    <td> <select  name="teamcompanyid" v-model="teamcompanyid"  class="form-control" v-on:change="switchCompany"> 
                    <option v-for="scompany in teamcompanies" v-bind:value="scompany.teamcompanyname.id" :key="scompany.teamcompanyname.id">
                    {{ scompany.teamcompanyname.companyname }}
                    </option>
                    </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Variation</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-creative-commons" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Company</th>
                        <th>Label</th>
                         <th>Value</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="varationl in variationlabels.data" :key="varationl.id">
                    <td>{{ varationl.companydata.companyname }}</td>
                    <td>{{ varationl.labeldata.vlabel }}</td>
                    <td>{{ varationl.variationname }}</td>
                    <td v-if="varationl.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/variation" data-id="varationl.id" @click="editModalWindow(varationl)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                       <a title="Do Inactive" v-if="varationl.isactive === 1" href="/#/variation" @click="inactiveLabel(varationl.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/variation" @click="activeLabel(varationl.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="variationlabels" @pagination-change-page="loadVariation"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateVariation() : createVariation()">
                  <div class="modal-body">
                      <div class="form-group">
                    <label for="unittype" class="rowlabel">Label</label>
                    <select name="vlabelid" v-model="form.vlabelid" id="vlabelid" class="form-control vlabelid" :class="{ 'is-invalid': form.errors.has('vlabelid') }" required>
                    <option  value="">Select Label</option>
                    <option v-for="vl in vlabel" v-bind:value="vl.id" :key="vl.id">
                    {{ vl.vlabel }}
                    </option>
                    </select>
                    <has-error :form="form" field="vlabelid"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="unittype" class="rowlabel">Value</label>
                    <input v-model="form.variationname" type="text" name="variationname"
                    placeholder="Value"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('variationname') }" required>
                    <has-error :form="form" field="variationname"></has-error>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <input type="hidden" v-model="form.systemid" name="systemid" />
                     <input type="hidden" v-model="form.entryid" name="entryid" />
                     <input type="hidden"   name="companyid" v-model="form.companyid">
                      <input type="hidden"   name="branchid" v-model="form.branchid">
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
       props: ['userData'],
        data() {
            return {
                editMode: false,
                variationlabels: {},
                vlabel: {},
                teamcompanyid:this.userData.companyid,
                teamcompanies:{}, 
                form: new Form({
                    id: '',
                    vlabelid : '',
                    variationname: '',
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    companyid: this.userData.companyid,
                    branchid: this.userData.branchid, 
                })
            }
        },
        methods: {
        editModalWindow(varationl){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(varationl)
        },
        updateVariation(){
           this.form.put('api/variation/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedLabelLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'This Variation Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Variation updated successfully'
                    })
                    Fire.$emit('AfterCreatedLabelLoadIt');
                    $('#addNew').modal('hide');
               }})
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           this.editMode = false
           this.form.reset();
           $('#addNew').modal('show');
        },
        loadVariation(page) {
          if (typeof page === 'undefined') {
             page = 1;
             }
            let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          axios.get('api/variation?page=' + page,{headers}).then( data => (this.variationlabels = data.data));
          //console.log("data",this.categories);
        },
         loadVariationlLabel() {
           let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            axios.get("/getvariationlabel",{headers})
          .then( data =>{
          this.vlabel = data.data;
          //this.productcode=1000;
          });
             
        },
        createVariation(){
          
            this.$Progress.start()
            this.form.post('api/variation')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedLabelLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'This Variation Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedLabelLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Variation created successfully'
                        })
                        this.$Progress.finish()
                        $('#addNew').modal('hide');
                         //location.reload();
                    }
                })
                .catch(() => {
                   console.log("Error......")
                })
            //this.loadUsers();
          },
          inactiveLabel(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, inactive it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('api/variation/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Variation inactive successfully',
                              'success'
                            )
                    this.loadVariation();
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
          },
          activeLabel(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, active it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('api/variation/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Variation active successfully',
                              'success'
                            )
                    this.loadVariation();
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
          },
          loadSwitchCompany() {
                let headers = {
                "Sessionkey": this.userData.remember_token,
                }
                axios.get('/getswitchcompany', {headers})
                .then( response =>{
                this.teamcompanies = response.data
                console.log("teamcompany =>", this.teamcompanies);
                });
            },
            switchCompany(event){
                let headers = {
                "Sessionkey": this.userData.remember_token,
                }
                let target = parseInt(event.target.value);
                axios.get("/updateSwitchCompany/"+target, {headers})
                .then( response =>{
                location.reload();
                // this.$router.go();
                Fire.$emit('AfterCreatedUserLoadIt'); //custom events
                });
            },
        },
        created() { //Like Mounted this method
             this.loadVariationlLabel();
             this.loadVariation();
             this.loadSwitchCompany();
            Fire.$on('AfterCreatedLabelLoadIt',()=>{ //custom events fire on
                this.loadVariation();
            });
            // setInterval(() => 
            //     this.loadUsers()
            // ,3000); //Every 3 seconds loadUsers call
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
