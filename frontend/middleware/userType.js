const data = async ({ $auth, redirect }) => {
  let userType = {
    user: "/alarm/dashboard",
    company: "/alarm/dashboard",
    customer: "/customer/dashboard",
    //security: "/security/dashboard",
    security: "/operator/dashboard",
    technician: "/technician/dashboard",
    //operator: "/technician/dashboard",
    master: "/master",
  };

  if ($auth.user.user_type == "company") {
    try {
      if (typeof window !== "undefined") {
        console.log("window.innerWidth ", window.innerWidth);

        if (window.innerWidth < 700) {
          window.location.href = "/alarm/mobiledashboard"; // Redirect for client-side navigation
        }
      }
    } catch (e) {}
    return redirect("/customer/dashboard"); // Fallback in case of null values
  }

  if ($auth.user.user_type === "master" || $auth.user.is_master === true) {
    return redirect("/master");
  }

  if (!$auth.user || !$auth.user.user_type) {
    return redirect("/alarm/dashboard"); // Fallback in case of null values
  }

  // if ($auth.user.user_type === "master" || $auth.user.is_master === true) {
  //   return redirect("/master");
  // }
  //console.log("$auth.user.user_type", $auth.user.user_type);

  redirect(userType[$auth.user.user_type] || "/alarm/dashboard");
};
export default data;
