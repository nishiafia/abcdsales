<template>
    <div class="container">
        <div class="row justify-content-center">
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
            <div class="page-header">
              <h2>{{ title }} </h2>
            </div>
            <div class="card-body table-responsive p-0">
              <p>{{ text }}</p>
              <div>
            <div v-if="this.userData.subscriptiondate === null && this.userData.usertype !== 'superadmin'" class="firstcompany">Please contact our support 09638010100 to unlock your access!</div>
            <div v-else-if="this.userData.companyid === 0 && this.userData.usertype !== 'superadmin'" class="firstcompany">You Need To Add Your Company First for All Access!!</div>
             <div v-else-if="currentdate >= subsdate && this.userData.usertype !== 'superadmin'" class="firstcompany">
            <p class="unaccess">Sorry your membership expired !!</p>
            <p >Please contact our support <span class="supportcontact">09638010100</span>   to unlock your access!</p>
             </div>
            <div v-else-if="this.userData.usertype === 'superadmin'" class="firstcompany"></div>
             <div v-else="currentdate <= subsdate && this.userData.usertype !== 'superadmin'" class="mt-5">
                <div id="a" class="divSquare"><div class="squaretext"><h1>0</h1><span> Total Sales</span></div></div>
                <div id="b" class="divSquare"><div class="squaretext"><h1>2</h1><span> Total Expenses</span></div></div>
                <div id="c" class="divSquare"><div class="squaretext"><h1>10</h1><span> Total Products</span></div></div>
                <div id="d" class="divSquare"><div class="squaretext"><h1>5</h1><span> Total Customers</span></div></div>
                <div class="clearfix"></div>
                      <div class="col-md-12">
                      <div class="card">

                      <div class="card-header">
                      <h3 class="card-title">Monthly Recap Report</h3>
                      </div>
                      <div class="card-body table-responsive p-0">
                      <table class="table table-hover">
                      <tbody>
                      <tr>
                      <td>Sales Today</td>
                      <td>0 BDT</td>
                      </tr>
                      <tr>
                      <td>Sales Last 7 days</td>
                      <td>1200 BDT</td>
                      </tr>
                      <tr>
                      <td>Sales Last 30 days</td>
                      <td>30000 BDT</td>
                      </tr>
                      </tbody></table>
                      </div>

                      <div class="card-footer">

                      </div>
                      </div>

                      </div>
                      </div>
             </div>
            </div>
           </div>
           </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
    export default {
        name: 'Dashboard',
        props: ['userData'],
        mounted() {
            console.log(this.userData['id'])
             console.log('Mounted')
        },
         data() {
            return {
             title: 'Dashboard',
             text: 'Welcome to ABCD Sales System',
             renderComponent: true,
             teamcompanyid:this.userData.companyid,
             teamcompanies:{},
              currentdate: moment().format("Y/M/D"),
              subsdate: moment(this.userData.subscriptiondate).format("Y/M/D"),
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
         },
          created() {
          this.loadSwitchCompany();
    }
    }
</script>