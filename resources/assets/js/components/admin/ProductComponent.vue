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
               <form  @submit.prevent="searchProduct()">
                 <table class="table">
                   <tbody>
                     <tr>
                         <td>
                           <label> Product Group: </label>
                        <select name="groupid" v-model="formsearch.groupid" id="groupid1" class="form-control"  :class="{ 'is-invalid': form.errors.has('groupid') }" required>
                        <option value="">Select Group</option>
                        <option v-for="gcodes in groupcodes" v-bind:value="gcodes.id" :key="gcodes.id">
                        {{ gcodes.gcode }} :: {{  gcodes.gtitle}}
                        </option>
                        </select>
                    </td>
                    <td>
                       <label> Product Type: </label> <br />
                      <label for="windows">
                      <input type="checkbox" id="purchaseproduct1" name="purchaseproduct" value="2" true-value="2" false-value="0" v-model="formsearch.purchaseproduct" >
                      Purchase Item
                      </label>
                      <label for="windows">
                      <input type="checkbox" id="salesproduct1" name="salesproduct" value="3" true-value="3" false-value="0" v-model="formsearch.salesproduct">
                      Sales Item
                      </label>
                     </td>
                      <td class="searchbutton">
                          <button   type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>
 Search </button></td>
                     </tr>
                   </tbody>
                 </table>
                    </form>
              <div class="card-header">
                <h3 class="card-title">Inventory</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal"  @click="openModalWindow">Add New <i class="fa fa-product-hunt" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Product</th>
                        <th>Group</th>
                        <th>Unit</th>
                        <th>Product Cost</th>
                        <th>Sell Price</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="product in products.data" :key="product.id">
                    <td>{{ product.productcode }} :: {{ product.productname }} </td>
                    <td>{{ product.groupname.gcode }} :: {{ product.groupname.gtitle }}</td>
                    <td>{{ product.productunit }} {{ product.punittype.unitname}}</td>
                    <td>{{ product.productcost}}</td>
                    <td >{{product.sellprice}}</td>
                    <td>
                        <a href="/#/product" title="Edit" data-id="product.id" @click="editModalWindow(product)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <router-link :to="'/product/' + product.id"  title="Product Detail">
                             <i class="fa fa-asterisk" aria-hidden="true"></i>
                        </router-link>
                        <!--a href="/#/product" title="Delete" @click="deleteProduct(product.id)">
                            <i class="fa fa-trash red"></i>
                        </a-->

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="products" @pagination-change-page="loadProduct"></pagination>
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

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Product</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Product</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                  <form @submit.prevent="editMode ? updateProduct() : createProduct()" id="createProductinfo">
                  <div class="modal-body">
                    <div class="form-group" v-show="editMode">
                      <label for="productcode">Product Code</label>
                      <input v-model="form.productcode" type="text"  name="productcode" readonly
                      class="form-control" :class="{ 'is-invalid': form.errors.has('productcode') }">
                      <has-error :form="form" field="productcode"></has-error>
                      </div>
                    <div class="form-group">
                    <label for="groupcode">Group Code
                      <router-link to="/groupcode" >
                      <i class="fa fa-plus-circle" aria-hidden="true" data-dismiss="modal"></i>
                    </router-link> </label>
                    <select name="groupid" v-model="form.groupid" id="groupid" class="form-control"  :class="{ 'is-invalid': form.errors.has('groupid') }" required>
                   <option value="">Select Group</option>
                    <option v-for="gcodes in groupcodes" v-bind:value="gcodes.id" :key="gcodes.id">
                    {{ gcodes.gcode }} :: {{  gcodes.gtitle}}
                    </option>
                    </select>
                    <has-error :form="form" field="groupid"></has-error>
                    </div>
                    <div class="form-group">
                      <label for="productname">Product Name</label>
                      <input v-model="form.productname" type="text"  name="productname"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('productname') }" required>
                      <has-error :form="form" field="productname"></has-error>
                      </div>
                    <div class="form-group">
                    <label for="details">Details</label>
                    <textarea v-model="form.details" name="details" class="form-control"  :class="{ 'is-invalid': form.errors.has('details') }" required ></textarea>
                    <has-error :form="form" field="details"></has-error>
                    </div>
                    <div class="row" >

                    <div class="form-group" >
                    <label for="unit">Unit</label>
                    <input v-model="form.productunit" type="number" name="productunit" id="productunit" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('productunit') }" @keypress="onlyNumber" required>
                    <has-error :form="form" field="productunit"></has-error>
                    </div>
                    <div class="form-group">
                    <label for="unittype" class="rowlabel">.</label>
                    <select name="unittype" v-model="form.unittype" id="unittype" class="form-control unittype" :class="{ 'is-invalid': form.errors.has('unittype') }" required>
                    <option  value="">Select Unit Type</option>
                    <option v-for="utype in utypes" v-bind:value="utype.id" :key="utype.id">
                    {{ utype.unitname }}
                    </option>
                    </select>
                    <has-error :form="form" field="unittype"></has-error>
                    </div>
                    </div>
                    <div class="form-group">
                    <label for="productcost">Product Cost</label>
                    <input v-model="form.productcost" type="text" name="productcost" id="productcost" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('productcost') }"  @keypress="onlyNumber" required>
                    <has-error :form="form" field="productcost"></has-error>
                    </div>
                    <div class="form-group">
                    <label for="sellprice">Sell Price</label>
                    <input v-model="form.sellprice" type="text" name="sellprice" id="sellprice" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('sellprice') }" @keypress="onlyNumber" required>
                    <has-error :form="form" field="sellprice"></has-error>
                    </div>
                      <div class="form-group" >
                      <label>Variations</label>
                      <div class="variationallselect"><input type="checkbox" v-on:change="selectAll" v-model="allSelected"> Select All</div>
                       <div class="row" >
                      <div v-for="varl in variationVal" :key="varl.id" class="variationrow">
                      <input type="checkbox" v-model="variationsid"  @change="select" :value="varl.id">
                      <span class="variationtitle">{{ varl.labeldata.vlabel }}:: </span>{{ varl.variationname }}
                      </div>
                      </div>
                    </div>
                    <div class="form-group" >
                    <label for="producttype">Product Type</label><br />
                            <label for="windows">
                                 <input type="checkbox" id="purchaseproduct" name="purchaseproduct" value="2" true-value="2" false-value="0" v-model="form.purchaseproduct">
                                  Purchase Item
                                  </label>
                                  <label for="windows">
                                  <input type="checkbox" id="salesproduct" name="salesproduct" value="3"  true-value="3" false-value="0" v-model="form.salesproduct">
                                  Sales Item
                                  </label>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <input type="hidden"   name="systemid" v-model="form.systemid">
                    <input type="hidden"   name="entryid" v-model="form.entryid">
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
import moment from 'moment';
    export default {
        props: ['userData'],
        data() {
            return {
                editMode: false,
                products: {},
                variations: {},
                utypes: {},
                groupcodes: {},
                pcolors: {},
                psizes: {},
                ptypes: {},
                shouldDisable: false,
                teamcompanyid:this.userData.companyid,
                teamcompanies:{},
                currentdate: moment().format("Y/M/D"),
                subsdate: moment(this.userData.subscriptiondate).format("Y/M/D"),
                form: new Form({
                    id: '',
                    productcode: '',
                    productname: '',
                    groupid : '',
                    details: '',
                    productunit : '',
                    unittype: '',
                    productcost: '',
                    vlabelid: [],
                    sellprice: '',
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    companyid: this.userData.companyid,
                    branchid: this.userData.branchid,
                    purchaseproduct: 2,
                    salesproduct: 0,
                }),
                formsearch: new Form({
                  groupid: '',
                  purchaseproduct: 2,
                  salesproduct: 0,
                }),
        selected: [],
        allSelected: false,
        variationsid: []
            }
        },
        computed: {
            variationVal(){
              var valdata = [];
              console.log("values=", this.variations);
              for(let i in this.variations){
                console.log("Variation single=", this.variations[i]);
                let singleVaria = this.variations[i];
                singleVaria.forEach(element => {
                  console.log("Variation single=", element);
                  valdata.push(element);
                });
              }
              return valdata;
            }
        },
        methods: {
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

          selectAll: function() {
            this.variationsid = [];
             console.log("all selected=",this.variationsid);
            if (this.allSelected) {
                for (let user in this.variationVal) {
                    this.variationsid.push(this.variationVal[user].id.toString());
                }
            }
        },
        select: function() {
            this.allSelected = false;
        },
        onlyNumber ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
            $event.preventDefault();
            }
            },
        onlyForCurrency ($event) {
            // console.log($event.keyCode); //keyCodes value
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);

            // only allow number and one dot
            if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || this.price.indexOf('.') != -1)) { // 46 is dot
            $event.preventDefault();
            }

            // restrict to 2 decimal places
            if(this.productcost!=null && this.productcost.indexOf(".")>-1 && (this.productcost.split('.')[1].length > 1)){
            $event.preventDefault();
            }
        },
        editModalWindow(product){
          if(this.currentdate <= this.subsdate && this.userData.subscriptionstatus === 1)
          {
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
           this.form.reset();
            this.variationsid=[];
           $('#addNew').modal('show');
           this.form.fill(product)
           for (let i = 0; i < product.variationdata.length; i++) {
            console.log("com=",product.variationdata[i].id);
            let vId=parseInt(product.variationdata[i].id,10);
            this.variationsid.push(vId);
          }
          }
          else{
             Fire.$emit('AfterCreatedProductLoadIt'); //custom events
                Toast.fire({
                icon: 'error',
                title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n to unlock your access.'
                })
          }
        },
        updateProduct(){
           this.form.put('api/product/'+this.form.id)
               .then((response)=>{
              console.log("response=",response.data);
               if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedProductLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Product Already Exists!!'
                        })
                    }
                    else{
                        axios.post('/createInventoryVariation', {inventoryId: this.form.id, variationInfo: this.variationsid})
                          .then((response) =>{
                          Toast.fire({
                          icon: 'success',
                          title: 'Product updated successfully'
                          })

                          Fire.$emit('AfterCreatedProductLoadIt');

                          $('#addNew').modal('hide');
                          })
                        }})
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           if(this.currentdate <= this.subsdate && this.userData.subscriptionstatus === 1)
          {
           this.editMode = false
           this.shouldDisable =false
           this.form.reset();
           $('#addNew').modal('show');
          }
           else{
              $('#addNew').modal('hide');
              Fire.$emit('AfterCreatedProductLoadIt'); //custom events
              Toast.fire({
              icon: 'error',
              title: 'Sorry your membership expired !! \n Please contact our support 09638010100 \n to unlock your access.'
              })
            }
        },

        loadProduct(page) {
          let headers = {
            "Sessionkey": this.userData.remember_token,
          }
          if (typeof page === 'undefined') {
            page = 1;
            }
          //console.log("token =", token);
          axios.get('api/product?page=' + page, {headers})
          .then( response =>{
              console.log("products =>", response.data);
              this.products = response.data
          });
            //debugger;
            //alert("adasds",data);
        },
        loadUnittype() {
          axios.get("/getunittype")
          .then( data =>{
          this.utypes = data.data;
          //this.productcode=1000;
          });
        },
         loadGroupcode() {
            let headers = {
            "Sessionkey": this.userData.remember_token,
            }
            axios.get("/getgroupcode",{headers})
            .then( data =>{
           // console.log("maxid =>", data);
            this.groupcodes = data.data;
            //this.productcode=1000;
            });
        },
         loadVariationLabel() {
            let headers = {
            "Sessionkey": this.userData.remember_token,
            }
            axios.get("/getvariation",{headers})
            .then( response =>{
           console.log("label =>", response.data);
           let datavariation=response.data;
           this.variations = response.data;
            //this.productcode=1000;
            });
        },

        createProduct(){
           let headers = {
            "Sessionkey": this.userData.remember_token,
          }
            var formContents = jQuery("#createProductinfo").serialize();
            this.$Progress.start()
            this.form.post('api/product',{headers})
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedProductLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Product Already Exists!!'
                        })
                    }
                    else if(response.data.message === 'expired')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedProductLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Sorry your membership expired !! \n Please contact to admin for access.'
                        })
                    }
                    else if(response.data.message === 'not')
                    {   // alert("not");
                          Fire.$emit('AfterCreatedProductLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Sorry you have no authorization!! \n Please contact to admin for access.'
                        })
                    }
                    else{
                      axios.post('/createInventoryVariation', {inventoryId: response.data, variationInfo: this.variationsid})
                      .then((response) =>{
                      console.log('response =', response.data);
                        Fire.$emit('AfterCreatedProductLoadIt'); //custom events

                        Toast.fire({
                        icon: 'success',
                        title: 'Product created successfully'
                        })

                        this.$Progress.finish()

                        $('#addNew').modal('hide');
                      });


                }})
                .catch(() => {
                   console.log("Error......")
                })



            //this.loadUsers();
          },
           searchProduct(){
            let headers = {
            "Sessionkey": this.userData.remember_token,
            }
            this.$Progress.start()

            this.formsearch.post('/searchProduct',{headers})
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedProductLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'No record found!!'
                        })
                    }
                    else{
                   this.products = response.data;
                    //this.loadProduct();
                    console.log("orders =>", this.products);

                }})
                .catch(() => {
                   console.log("Error......")
                })



            //this.loadUsers();
          },
          deleteProduct(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

              if (result.value) {
                //Send Request to server
                this.form.delete('api/product/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Product deleted successfully',
                              'success'
                            )
                     this.loadProduct();

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
          this.loadVariationLabel();
          this.loadGroupcode();
          this.loadUnittype();
          this.loadProduct();
          this.loadSwitchCompany();
          Fire.$on('AfterCreatedProductLoadIt',()=>{ //custom events fire on
              this.loadProduct();
          });
        }
    }
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}
.row{

    margin-left: 0px;
}
.rowlabel{
    color:white;
}
.unittype{
    margin-left: 20px;
}
.pagination{
  margin:15px;
}

</style>