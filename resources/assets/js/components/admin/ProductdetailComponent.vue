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
                <h3 class="card-title">Product Detail</h3>
               
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                   
                  <tbody v-for="product in products" :key="product.id">
                    <tr>
                        <td class="tabletitle">Product Code :</td> 
                        <td>{{product.productcode}} :: {{product.productname}}</td>
                    </tr>
                    <tr>
                        <td class="tabletitle">Group :</td> 
                        <td>{{product.groupname.gcode}} :: {{product.groupname.gtitle}}</td> 
                    </tr>
                     <tr>
                        <td class="tabletitle">Company :</td> 
                        <td>{{product.companydata.companyname}}</td>
                    </tr>
                    <tr>
                        <td class="tabletitle">Details :</td> 
                        <td>{{product.details}}</td>
                    </tr>
                    <tr>
                        <td class="tabletitle">Product Unit :</td> 
                        <td>{{product.productunit}} {{product.punittype.unitname}}</td> 
                    </tr>
                    <tr>
                        <td class="tabletitle">Product Cost :</td> 
                        <td>{{product.productcost || currency}}</td> 
                    </tr>
                    <tr>
                        <td class="tabletitle">Sell Price :</td> 
                        <td>{{product.sellprice}}</td>  
                    </tr>
                   
                    <tr>
                        <td class="tabletitle">Variation :</td> 
                        <td  class="row">
                            <div class="variationdetail" v-for="productval in product.variationdatadetail" :key="productval.id"> 
                            

                            <span class="variationtitle"> {{productval.labeldata.vlabel}}:: </span> {{productval.valdata.variationname}}
                                  
                            </div>
                            </td>  
                    </tr>
                    <tr>
                        <td class="tabletitle">Type :</td> 
                        <td><span v-if="product.purchaseproduct !==0 && product.salesproduct !==0">Purchase Item/Sales Item</span>
                        <span v-else-if="product.purchaseproduct !==0 && product.salesproduct === 0">Purchase Item</span>
                        <span v-else-if="product.purchaseproduct ===0 && product.salesproduct !== 0">Sales Item</span>
                        </td>  
                    </tr>
                     <tr>
                        <td><a @click="$router.go(-1)" class="btn btn-danger">Back</a></td> 
                        <td></td>  
                    </tr>
                 </tbody>
                
                </table>
               
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
        props: ['userData','pid'],
        data() {
            return {
             
                products: {},
                utypes: {},
                groupcodes: {},
                pcolors: {},
                psizes: {},
                ptypes: {},
                sysid: this.userData.remember_user ,
                teamcompanyid:this.userData.companyid,
                teamcompanies:{}, 
               
            }
        },
        /* computed: {  
          productcode: {
            get: function () { return  this.userData.id },
            set: function (newValue) { this.productcode = newValue}
          }  
         },  */
        methods: {
        onlyNumber ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
            $event.preventDefault();
            }
            },
 

        editModalWindow(product){
           this.form.clear();
           this.editMode = true
            this.shouldDisable =true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(product)
           
        },
        updateProduct(){
           this.form.put('/product/'+this.form.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Product updated successfully'
                    })

                    Fire.$emit('AfterCreatedProductLoadIt');

                    $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           this.editMode = false
           this.shouldDisable =false
           this.form.reset();
           $('#addNew').modal('show');
        },

        loadProduct() { 
          let headers = {
            "Sessionkey": this.userData.remember_user,
          }
          axios.get("/product/"+this.pid, {headers})
          .then( response =>{
              console.log("products =>", response.data);
              this.products = response.data
          });
            
            //debugger;
            //alert("adasds",data);
        },
      loadSwitchCompany() {
                let headers = {
                "Sessionkey": this.userData.remember_user,
                }
                axios.get('/getswitchcompany', {headers})
                .then( response =>{
                this.teamcompanies = response.data
                console.log("teamcompany =>", this.teamcompanies);
                });
            },
            switchCompany(event){
                let headers = {
                "Sessionkey": this.userData.remember_user,
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
.tabletitle{
    width: 20%;
}

</style>