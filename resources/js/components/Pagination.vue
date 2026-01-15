<template>
    <div class="flex flex-col md:flex-row md:justify-between items-center mt-5 gap-4">
        <div class="font-14">
            แสดง <span class="text-blue-600">{{ from  }}</span> ถึง  <span class="text-blue-600">{{ to }}</span> จาก <span class="text-blue-600">{{ total }}</span>  รายการ
        </div>
        <ul class="flex items-center space-x-1 text-sm">
            <li v-for="(item, i) in items" :key="i">
                <button v-if="item.class == 'active'" class="px-3 py-1 rounded border bg-[#FB8C00] text-white">{{ item.name }}</button>
                <template v-else>
                    <button v-if="item.name == 'back'" @click="gotoPage(item.page)" class="px-3 py-1 rounded border text-gray-600 hover:bg-gray-100" aria-label="Previous">
                        &laquo;
                    </button>
                    <button v-else-if="item.name == 'next'" @click="gotoPage(item.page)" class="px-3 py-1 rounded border text-gray-600 hover:bg-gray-100" aria-label="Next">
                        &raquo;
                    </button>
                    <button v-else @click="gotoPage(item.page)" class="px-3 py-1 rounded border hover:bg-gray-100">{{ item.name }}</button>
                </template>
            </li>
        </ul>
    </div>

</template>
<script>
export default {
    data() {
        return {
            from: '',
            to: '',
            total: '',
            items: [],
            currentpage: ''
        }
    },
    components: {
    },
    props: ['limit'],
    created() {
    },
    methods: {
        createPagination() {
            var count = this.total
            if (count >= 1) {
                this.items = []
                var currentPage = this.currentpage, // input
                    range = 10,  // amount of links displayed 
                    start = 1;  // default
                var totalPages = parseFloat(count / this.limit);
                if (this.total > 0 && totalPages == 0) {
                    totalPages = 1
                }
                totalPages = Math.ceil(totalPages);

                if (totalPages < range) {
                    range = totalPages;
                }
                var html = "";

                if (currentPage < (range / 2) + 1) {
                    start = 1;
                } else if (currentPage >= (totalPages - (range / 2))) {
                    start = Math.floor(totalPages - range + 1);

                } else {
                    start = (currentPage - Math.floor(range / 2));
                }

                if (currentPage > 1) {
                    var back = currentPage - 1;
                    var item_2 = { class: '', name: "back", page: back }
                    this.items.push(item_2);
                }

                for (var i = start; i <= ((start + range) - 1); i++) {
                    if (i == currentPage) {
                        var item_2 = { class: 'active', name: i, page: i }
                        this.items.push(item_2);
                    } else {
                        var item_2 = { class: '', name: i, page: i }
                        this.items.push(item_2);
                    }
                }

                if (currentPage < totalPages) {
                    var next = Math.floor(currentPage) + 1;
                    var item_2 = { class: '', name: "next", page: next }
                    this.items.push(item_2);
                }
            }
        },
        gotoPage(page) {
            this.$emit('eventPage', page)
        }
    }
}
</script>
<style>
.pagination>li>a {
    padding: 4px 12px !important;
}
</style>