<template>
    <div class="multiselect">
        <div class="search-box">
            <input type="text" v-model="search" class="form-control" :placeholder="searchPlaceholder" />
        </div>
        <div class="content_wrapper"  :style="{ maxHeight: height + 'px' }">
            <ul class="list-unstyled items">
                <li class="item" v-for="(item, index) in filteredItems" :key="index">
                    <label>
                        <input type="checkbox" :name="name" :value="item[valueAttribute]" v-model="value" />
                        <slot name="item" :item="item"></slot>
                    </label>
                </li>
            </ul>
        </div>
        <div class="multiselect-footer">
            {{ value.length }} item selected.
        </div>
    </div>
</template>

<script>
    export default {
        name: "MultiSelect",
        props: {
            height: Number,
            name: String,
            value: Array,
            items: {
                type: Array,
                default() {
                    return []
                }
            },
            searchAttribute: [String, Array],
            searchPlaceholder: String,
            valueAttribute: String,
            sortBy: {
                type: String,
                default() {
                    return this.valueAttribute;
                }
            },
            sortDirection: {
                type: String,
                default() {
                    return 'asc'
                }
            }
        },
        data() {
            return {
                search: '',
            }
        },
        computed: {
            filteredItems() {
                let q = this.search.toLowerCase();
                let items = this.items;

                if(q.length && this.searchAttribute)
                    items =  this.items.filter(item => {
                        let string = item[this.searchAttribute];
                        return string.toLowerCase().indexOf(q) !== -1
                    });

                return items.sort((a,b) => a[this.sortBy].toLowerCase() < b[this.sortBy].toLowerCase() ? -1 : 0);
            }
        }
    }
</script>

<style scoped lang="scss">
    .multiselect {
        .search-box {
            .form-control {
                border: 0;
                border-bottom: 1px solid;
                box-shadow: none;
                border-radius: 0;
            }
        }

        .content_wrapper {
            overflow-y: auto;
            padding: 1rem 0;

            label {
                display: block;
                padding: 5px 15px;

                &:hover {
                    cursor: pointer;
                    background-color: #efefef;
                }
            }

            input[type="checkbox"] {
                margin-right: 5px;
            }

            ul {
                margin-bottom: 0;
            }
        }

        &-footer {
            padding: 10px 15px;
            border-top: 1px solid #efefef;
            color: #777;
            font-size: 85%;
        }
    }
</style>