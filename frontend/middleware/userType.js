const data = async ({ $auth, redirect }) => {

  let userType = {
    "company": "/alarm/dashboard",
    "customer": "/customer/dashboard",
  }

  redirect(userType[$auth.user.user_type] || "master");
}
export default data;
