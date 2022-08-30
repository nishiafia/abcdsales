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
                <h3 class="card-title">Change Password</h3>
              </div>
              <div class="card-body table-responsive p-0">
                 <form @submit.prevent="submitPassword()" id="changepass" >
                        <div class="modal-body">
                          <div class="form-group">
                            <label for="oldpass">Old Password</label>
                               <input v-model="form.oldpassword" type="password" class="form-control" id="oldpassword" placeholder="********" name="oldpassword" required>
                              <input type="hidden" v-model="form.uid" name="uid" >
                          </div>
                           <div class="form-group">
                            <label for="oldpass">New Password</label>
                               <input v-model="form.password" type="password" class="form-control" id="password" placeholder="********" name="password" required>
                          </div>
                          <div class="form-group">
                            <label for="oldpass">Confirm Password</label>
                               <input v-model="form.confirmpassword" type="password" class="form-control" id="confirmpassword" placeholder="********" name="confirmpassword" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                        <button  type="submit" class="btn btn-primary">Change</button>
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
              max:12,
            form: new Form({
                uid: this.userData.id,
                oldpassword:'',
                password: '',
                confirmpassword: '', 
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
          onlyNumber ($event) {
          let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
          if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
          $event.preventDefault();
          }
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
          loadBusinesscategory() {
            axios.get('/getbusinesscategory').then( data => (this.categories = data.data));
            console.log("category=", this.categories);
          },

        submitPassword: function (e){
           var formContents = jQuery("#changepass").serialize();
           axios.post('/changeUserPassword',formContents)
               .then((response)=>{
                  console.log("response=", response.data.mgs);
                  if(response.data.mgs === 'success')
                  {
                   Toast.fire({
                      icon: 'success',
                      title: 'Password Changed successfully'
                    })
                      axios.get('/logout').then(response => {
                        console.log("response1=", response);
                        this.$router.push({ path: "/" });
                        location.reload();

                    })
                  }
                   else if(response.data.mgs === 'not')
                  {
                   Toast.fire({
                      icon: 'error',
                      title: 'Wrong old Password'
                    })
                  }
                    else if(response.data.mgs === 'notmatch')
                  {
                   Toast.fire({
                      icon: 'error',
                      title: 'Confirmed Password not Match'
                    })
                  }
                  
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
              Fire.$on('AfterCreatedUserLoadIt'); //custom events
        }
    }
</script> 
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}

</style>