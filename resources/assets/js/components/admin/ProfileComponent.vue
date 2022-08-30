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
                <h3 class="card-title">Profile</h3>
              </div>
              <div class="card-body table-responsive p-0">
                 <form @submit.prevent="updateProfile()" >
                        <div class="modal-body">
                          <div class="form-group">
                            <label for="phone">Name</label>
                            <input v-model="form.name" type="text" name="name"
                            placeholder="Name"
                            class="form-control">
                            </div>
                            <div class="form-group">
                            <label for="phone">Username(phone)</label>
                            <div class="telephoneformat">Example Format: ( 01712234678 )</div>
                         <!--VuePhoneNumberInput
                        default-country-code="BD"
                        name="telephone"
                        required
                        v-model="form.telephone"
                        disabled
                         :maxlength="max"
                          @update="updatePhoneNumber"
                         /-->
                         <input v-model="form.telephone" type="tel" name="telephone" maxlength="11" minlength="11" required placeholder="telephone" @keypress="onlyNumber" class="form-control" >
                          </div>
                          <div class="form-group">
                            <label for="phone">Email</label>
                            <input v-model="form.email" type="email" name="email" readonly 
                            class="form-control">
                          </div>
                          <!--div class="form-group">
                        <label for="business">Business Category</label>
                        <select name="businesscategory" v-model="form.businesscategory" id="businesscategory" class="form-control"  :disabled="this.userData.usertype === 'team'">
                          <option value="">Select Business Categories</option>
                          <option v-for="bcategory in categories" v-bind:value="bcategory.id" :key="bcategory.id">
                          {{ bcategory.categoryname }}
                          </option>
                        </select>
                        <has-error :form="form" field="usertype"></has-error>
                      </div-->
                        <div class="form-group">
                            <label for="phone">Address</label>
                            <input v-model="form.address" type="text" name="address"
                            class="form-control"  :readonly="this.userData.usertype === 'team'">
                        </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden"   name="usertype" v-model="form.usertype">
                            <input type="hidden"   name="dialcode" v-model="form.dialcode">
                            <input type="hidden"   name="systemid" v-model="form.systemid">
                             <input type="hidden"   name="isactive" v-model="form.isactive">
                        <button  type="submit" class="btn btn-primary">Update</button>
                        </div>
                      </form>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>
<script>
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
    export default {
      components: {  VuePhoneNumberInput},
      //  props: ['id'],
        props: ['userData'],
        data() {
            return {
              categories: {},
              teamcompanyid:this.userData.companyid,
              teamcompanies:{},
              max:11,
            form: new Form({
                id: this.userData.id,
                name:this.userData.name,
                dialcode: this.userData.dialcode,
                telephone: this.userData.telephone,
                email: this.userData.email,
                usertype: this.userData.usertype,
                address: this.userData.address,
                systemid: this.userData.systemid,
               // businesscategory: this.userData.businesscategory,
                companyid: this.userData.companyid,
                isactive: this.userData.isactive,
            })

            }
        },
       /* computed: {
          id: {
            get: function () { return  this.userData.id },
            set: function (newValue) { this.id = newValue}
          },
          name: {
            get: function () { return  this.userData.name },
            set: function (newValue) { this.name = newValue}
          },
          dialcode: {
            get: function () { return  this.userData.dialcode },
            set: function (newValue) { this.dialcode = newValue}
          },
           telephone: {
            get: function () { return  this.userData.telephone },
            set: function (newValue) { this.telephone = newValue}
          },
          email: {
            get: function () { return  this.userData.email },
            set: function (newValue) { this.email = newValue}
          },
           usertype: {
            get: function () { return  this.userData.usertype },
            set: function (newValue) { this.usertype = newValue}
          },
          address: {
            get: function () { return  this.userData.address },
            set: function (newValue) { this.address = newValue}
          }
        },*/
        methods: {
          updatePhoneNumber(data) {
               this.form.dialcode=data.countryCallingCode;
               console.log("dialcode=", this.form.dialcode);
            },
          onlyNumber ($event) {
          let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
          if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
          $event.preventDefault();
          }
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
          loadBusinesscategory() {
            axios.get('/getbusinesscategory').then( data => (this.categories = data.data));
            console.log("category=", this.categories);
          },

        updateProfile(){
           this.form.put('api/user/'+this.form.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Profile updated successfully'
                    })

                    Fire.$emit('AfterCreatedUserLoadIt');

                   // $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        }
        },
      
        created() { //Like Mounted this method
          this.loadBusinesscategory();
          this.loadSwitchCompany();
            /* this.id = this.userData.id;
             this.name = this.userData.name;
             this.telephone = this.userData.telephone;
             this.email = this.userData.email;
             this.usertype = this.userData.usertype;
             this.address = this.userData.address;*/
        }
    }
</script> 
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}

</style>