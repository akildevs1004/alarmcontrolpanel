<template>
  <div>
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" top="top" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>

    <v-card class="elevation-0 p-2" style="padding: 5px">
      <v-row>
        <v-col cols="12" style="height: 300px">
          <v-row class="pt-0">
            <v-col>
              <h3>Subject: {{ payload_ticket.subject }}</h3>
            </v-col>
            <v-col
              cols="12"
              dense
              style="height: 270px; border: 1px solid #ddd"
            >
              <div v-html="payload_ticket.subject"></div>
            </v-col>
          </v-row>
        </v-col>
      </v-row>

      <v-row style="margin-top: 15px">
        <v-col cols="6">
          <h3>Attachments({{ editItem?.attachments?.length ?? 0 }})</h3></v-col
        >
        <v-col cols="6">
          <a
            class="pr-5"
            v-if="editItem?.attachments"
            v-for="attachment in editItem.attachments"
            title="Download Profile Picture"
            :href="getDonwloadLink(attachment.ticket_id, attachment.attachment)"
            >{{ attachment.title }}
            <v-icon color="violet">mdi-arrow-down-bold-circle</v-icon></a
          >
        </v-col>
      </v-row>
    </v-card>

    <v-row>
      <v-col cols="12" class="text-right">
        <TicketResponses
          :expandPanels="false"
          v-if="editItem"
          :ticket="editItem"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script>
import TicketResponses from "./TicketResponses.vue";
export default {
  props: ["editItem", "editId"],
  components: { TicketResponses },
  data: () => ({
    snack: false,
    snackColor: "",
    snackText: "",

    //end editor
  }),
  created() {
    this.payload_ticket = { subject: "", description: "" };

    if (this.editId != "" && this.editItem) {
      this.payload_ticket.subject = this.editItem.subject;
      //this.payload_ticket.description = this.editItem.description;
    }
  },

  methods: {
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    getDonwloadLink(ticket_id, file_name) {
      return (
        process.env.BACKEND_URL +
        "/download_ticket_atachment/" +
        ticket_id +
        "/" +
        file_name
      );
    },
  },
};
</script>
