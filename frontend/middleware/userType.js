const data = async ({ $auth, redirect }) => {
  let userType = {
    company: "/alarm/dashboard",
    customer: "/customer/dashboard",
    security: "/security/dashboard",
  };

  if ($auth.user.user_type === "master" || $auth.user.is_master === true) {
    return redirect("/master");
  }
  redirect(userType[$auth.user.user_type] || "/master");
};
export default data;
