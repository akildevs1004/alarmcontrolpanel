const data = async ({ $auth, redirect }) => {
  let userType = {
    user: "/alarm/dashboard",
    company: "/alarm/dashboard",
    customer: "/customer/tickets",
    //security: "/security/dashboard",
    security: "/operator/dashboard",
    technician: "/technician/dashboard",
    //operator: "/technician/dashboard",
  };

  if ($auth.user.user_type === "master" || $auth.user.is_master === true) {
    return redirect("/master");
  }

  if (!$auth.user || !$auth.user.user_type) {
    return redirect("/master"); // Fallback in case of null values
  }

  if ($auth.user.user_type === "master" || $auth.user.is_master === true) {
    return redirect("/master");
  }
  //console.log("$auth.user.user_type", $auth.user.user_type);

  redirect(userType[$auth.user.user_type] || "/master");
};
export default data;
