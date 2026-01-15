<template>
  <div class="mt-6 mb-11">
    <loading :active="isLoading" :can-cancel="false" :is-full-page="true" :color="'#3fbbc0'" :loader="'spinner'" :width="64" :height="64" />

    <div class="flex mt-11">
      <table class=" w-full border border-[#dee2e6] text-left text-sm">
        <thead>
            <tr>
                <th width="10%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ระดับความรุนแรง</th>
                <th width="60%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">รายละเอียด</th>
                <th width="15%" class="px-4 py-2 border border-[#dee2e6] text-center" scope="col">ระดับความรวดเร็วในการตอบสนองข้อคิดเห็น</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in items" class="hover:bg-[#efefef]">
              <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ item.level }}</td>
              <td class="px-4 py-2 border border-[#dee2e6]">{{ item.name }}</td>
              <td class="px-4 py-2 border border-[#dee2e6] text-center">{{ item.time }} วัน</td>
              
            </tr>
          </tbody>
      </table>
    </div>

    <div class="gap-2 flex justify-end mt-11">
        <a href="/admin/setting" class="px-4 py-2 border rounded hover:cursor-pointer">ย้อนกลับ</a>
    </div>


  </div>
</template>
<script>
export default {
  name: 'SettingSeverityComponent',
  data() {
    return {
      isLoading: false,
      items: [],
    }
  },
  mounted() {
    this.showData();
  },
  methods: {
    showData(){
      this.isLoading = true;
      axios.get('/admin/setting/severity/load') 
      .then(res => {
        if(res.data.status == 200){
          this.items = res.data.item
          this.isLoading = false;
        }else{
          this.items = [];
          this.isLoading = false;
        }
      })
      .catch(err => {
        console.error(err)
        this.isLoading = false;
      })
    },
  }
}
</script>
