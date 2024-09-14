<template>
  <div>
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" top="top" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <template class="pt-3 text-left">
      <h3>All Responses({{ ticket ? ticket.responses?.length : 0 }})</h3>
      <v-expansion-panels
        v-model="panel"
        multiple
        focusable
        v-if="ticket?.responses"
      >
        <v-expansion-panel v-for="(item, i) in ticket.responses" :key="i">
          <v-expansion-panel-header style="min-height: 25px">
            <v-row>
              <v-col cols="6"
                ><div v-if="item.customer_id == $auth.user.customer.id">
                  Me
                </div></v-col
              >
              <v-col cols="6" style="text-align: right">
                {{ item.created_datetime }}</v-col
              >
            </v-row>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <div style="text-align: left" v-html="item.description"></div>

            <v-row
              ><v-col
                ><v-row style="margin-top: 15px">
                  <v-col cols="12">
                    Attachments({{ item?.attachments?.length ?? 0 }})

                    <a
                      class="pr-5"
                      v-if="item?.attachments"
                      v-for="attachment in item.attachments"
                      title="Download Profile Picture"
                      :href="
                        getDonwloadLink(
                          attachment.ticket_response_id,
                          attachment.attachment
                        )
                      "
                      >{{ attachment.title }}
                      <v-icon color="violet"
                        >mdi-arrow-down-bold-circle</v-icon
                      ></a
                    >
                  </v-col>
                </v-row></v-col
              ></v-row
            >
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </template>
  </div>
</template>

<script>
export default {
  props: ["ticket", "expandPanels"],

  data: () => ({ snackbar: false, response: "", panel: [] }),
  created() {
    if (this.ticket && this.expandPanels)
      for (var i = 0; i < this.ticket.responses.length; i++) {
        this.panel.push(i);
      }
  },

  methods: {
    getDonwloadLink(ticket_reponse_id, file_name) {
      return (
        process.env.BACKEND_URL +
        "/download_ticket_response_atachment/" +
        ticket_reponse_id +
        "/" +
        file_name
      );
    },
  },
};
</script>
