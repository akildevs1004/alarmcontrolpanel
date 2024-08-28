export default ({ $axios, store }, inject) => {
  $axios.onRequest(async (config) => {
    let user = store.state.auth.user;

    if (user) {
      config.params = {
        ...config.params,
        company_id: user.company_id,
      };
    }

    if (user?.role?.role_type == "guard") {
      if (user && user.employee && user.employee.branch_id > 0) {
        config.params = {
          ...config.params,
          branch_id: user.employee.branch_id,
        };
      }
    }
    if (user && user.branch_id && user.branch_id > 0) {
      config.params = {
        ...config.params,
        branch_id: user && user.branch_id,
      };
    }

    if (user && user.user_type == "department") {
      config.params = {
        ...config.params,
        department_id: user && user.department_id,
        user_type: user && user.user_type,
      };
    }
    if (user && user.user_type == "security") {
      let customersList = user.security.customers_assigned.map(
        (e) => e.customer_id
      );

      config.params = {
        ...config.params,
        filter_customers_list: customersList,
        user_type: user && user.user_type,
      };
    }
    return config; // Return the modified config
  });
};
