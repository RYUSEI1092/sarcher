<template>     
    <div class="detail">
        <img class="thumb" :src="books.largeImageUrl" alt="蔵書サムネイル">
        <ul class="items">
            <li class="title">{{ books.title }}</li>
            <li>貸出状況</li><li><span class="icon_span" v-if="rental"><img class="icon" src="../../images/true.png" alt="" srcset=""></span><span class="icon_span" v-else><img class="icon" src="../../images/false.png" alt="" srcset=""></span></li>
            <li>図書館</li>
            <li>
                <select :disabled="!rental || !isAuthorized" v-model="selectValue" :class='{selectDisabled:!rental || !isAuthorized}' form="form" class="select_library" name="library">
                    <option v-if="!rental || !isAuthorized" value="">レンタルできません</option>
                    <option v-else value="">レンタル先図書館選択</option>
                    <template v-for="(isRentable,libName) in libraries" :key="libName">
                        <option v-if="libName !== 'system_id'" :disabled="!isRentable" :value="libName">{{libName}}</option>
                    </template>
                </select>
            </li>
            <li>ジャンル</li><li class="item">{{books.genreName}}</li>
            <li>著者</li><li class="item">{{books.author}}</li>
            <li>サイズ</li><li class="item">{{books.size}}</li>
            <li>出版日</li><li class="item">{{books.salesDate}}</li>
            <li>レビュー</li><li class="item"><span class="rating" :data-rate="Number.parseFloat(books.reviewAverage).toFixed(1)"></span>({{books.reviewAverage}})</li>
        </ul>
        <a v-if="!isAuthorized" class="login_link_text" href="/login">※レンタル機能を使用するには<span class="login_link">ログイン</span>が必要です。</a>
        <p class="caption">{{books.itemCaption}}</p>
    </div>

    <form :action="`./${books.isbn}`" id="form" method="post">
        <input type="hidden" name="_token" :value="csrf" />
        <input type="hidden" name="isbn" :value="books.isbn">
        <input type="hidden" name="book_img" :value="books.largeImageUrl">
        <input type="hidden" name="book_title" :value="books.title">
        <input type="hidden" name="book_genre" :value="books.genreName">
        <input type="hidden" name="book_author" :value="books.author">
        <input type="hidden" name="publisher_name" :value="books.publisherName">
        <input type="hidden" name="system_id" :value="libraries.system_Id">
        <input :disabled="!rental || !isAuthorized || !selectValue" :class="{'rental_form_enable': rental && isAuthorized && selectValue, 'rental_form_disable': !rental || !isAuthorized || !selectValue }" type="submit" value="レンタル">
    </form>
    
    <ConfirmModal
      :isShow="isShowRentalBook"
      message="レンタルが完了しました"
      left-message="検索"
      left-icon="search"
      right-message="トップ"
      right-icon="home"
      @close="onClose"
      @click-left="toSearch"
      @click-right="toHome"
    />
</template>

<script>
import ConfirmModal from "~/components/modal/ConfirmModal.vue";

export default {
    components: { ConfirmModal },
    props: {
        csrf: {
            type: String,
            default: () => "",
        },
        isAuthorized: {
            type: Boolean,
            default: () => false,
        },
        books: {
            type: Object,
            default: () => []
        },
        libraries: {
            type: Array,
            default: () => []
        },
        isAuthorized: {
            type: Boolean,
            default: () => false
        },
        isCreate: {
            type: Boolean,
            default: () => false
        }
    },
    data(){
        return {
            selectValue : "" ,
            isShowRentalBook : false
        }
    },
    created(){
        this.isShowRentalBook = this.isCreate
    },
    computed:{
        rental(){
            return Object.values(this.libraries).some((library) => library == true);
        }
    },
    methods:{
        onClose(){
            return this.isShowRentalBook = false;
        },
        toSearch() {
            location.href="/books";
        },
        toHome() {
            location.href="/";
        },
    }
}
</script>


<style lang="scss" scoped>
.detail{
    margin: 0 32px;
    ul.items {
        display: grid;
        grid-template-columns: 104px calc(100% - 148px);
        grid-template-rows: auto;
        grid-column-gap: 0px;
        grid-row-gap: 0px;
        list-style: none;
        padding-left: 0px;
        li{
            display: flex;
            align-items: center;
            padding-bottom: 14px;
            line-height: 14px;
            font-size: 14px;
            color: #9b9b9b;
            span.icon_span{
                img{
                    width: 39px;
                    height: 18px;
                }
            }
            span.icon_span{
                img{
                    width: 39px;
                    height: 18px;
                }
            }
            .selectDisabled{
                background: #D3D3D3;
                content : "";
            }
            .select_library{
                width: 250px;
                text-align: center;
                vertical-align: middle;
                border: 1px solid;
                color: black;
            }
        }
        li.title{
            grid-area: 1 / 1 / 2 / 3;
            padding-top: 16px;
            line-height: 18px;
            color: black;
            font-size: 18px;
            font-weight: bold;
        }
        li.item{
            color: black;
        }
    }
    a.login_link_text{
        text-decoration: none;
        color: red;
        span{
            color: #0029FF;
        }
    }
    p.caption{
        margin-top: 40px;
        padding-bottom: 114px;
    }
}
.rating{
    line-height: 14px;
    position: relative;
    z-index: 0;
    display: inline-block;
    white-space: nowrap;
    color: #d9d9d9;
}
.rating:before, .rating:after{
    content: '★★★★★';
}
.rating:after{
    position: absolute;
    z-index: 1;
    top: 0;
    left: 0;
    overflow: hidden;
    white-space: nowrap;
    color: #ffcf32;
}
form#form{
    position: fixed;
    bottom: 50px;
    height: 64px;
    width: 100%;
    text-align: center;
    line-height: 64px;
    .rental_form_enable{
        width: 100%;
        background: #0029FF;
        border: none;
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
    .rental_form_disable{
        width: 100%;
        background: #9b9b9b;
        border: none;
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
}
.rating[data-rate="0.0"]:after{ width: 0%; }
.rating[data-rate="0.1"]:after{ width: 2%; }
.rating[data-rate="0.2"]:after{ width: 4%; }
.rating[data-rate="0.3"]:after{ width: 6%; }
.rating[data-rate="0.4"]:after{ width: 8%; }
.rating[data-rate="0.5"]:after{ width: 10%; }
.rating[data-rate="0.6"]:after{ width: 12%; }
.rating[data-rate="0.7"]:after{ width: 14%; }
.rating[data-rate="0.8"]:after{ width: 16%; }
.rating[data-rate="0.9"]:after{ width: 18%; }
.rating[data-rate="1.0"]:after{ width: 20%; }
.rating[data-rate="1.1"]:after{ width: 22%; }
.rating[data-rate="1.2"]:after{ width: 24%; }
.rating[data-rate="1.3"]:after{ width: 26%; }
.rating[data-rate="1.4"]:after{ width: 28%; }
.rating[data-rate="1.5"]:after{ width: 30%; }
.rating[data-rate="1.6"]:after{ width: 32%; }
.rating[data-rate="1.7"]:after{ width: 34%; }
.rating[data-rate="1.8"]:after{ width: 36%; }
.rating[data-rate="1.9"]:after{ width: 38%; }
.rating[data-rate="2.0"]:after{ width: 40%; }
.rating[data-rate="2.1"]:after{ width: 42%; }
.rating[data-rate="2.2"]:after{ width: 44%; }
.rating[data-rate="2.3"]:after{ width: 46%; }
.rating[data-rate="2.4"]:after{ width: 48%; }
.rating[data-rate="2.5"]:after{ width: 50%; }
.rating[data-rate="2.6"]:after{ width: 52%; }
.rating[data-rate="2.7"]:after{ width: 54%; }
.rating[data-rate="2.8"]:after{ width: 56%; }
.rating[data-rate="2.9"]:after{ width: 58%; }
.rating[data-rate="3.0"]:after{ width: 60%; }
.rating[data-rate="3.1"]:after{ width: 62%; }
.rating[data-rate="3.2"]:after{ width: 64%; }
.rating[data-rate="3.3"]:after{ width: 66%; }
.rating[data-rate="3.4"]:after{ width: 68%; }
.rating[data-rate="3.5"]:after{ width: 70%; }
.rating[data-rate="3.6"]:after{ width: 72%; }
.rating[data-rate="3.7"]:after{ width: 74%; }
.rating[data-rate="3.8"]:after{ width: 76%; }
.rating[data-rate="3.9"]:after{ width: 78%; }
.rating[data-rate="4.0"]:after{ width: 80%; }
.rating[data-rate="4.1"]:after{ width: 82%; }
.rating[data-rate="4.2"]:after{ width: 84%; }
.rating[data-rate="4.3"]:after{ width: 86%; }
.rating[data-rate="4.4"]:after{ width: 88%; }
.rating[data-rate="4.5"]:after{ width: 90%; }
.rating[data-rate="4.6"]:after{ width: 92%; }
.rating[data-rate="4.7"]:after{ width: 94%; }
.rating[data-rate="4.8"]:after{ width: 96%; }
.rating[data-rate="4.9"]:after{ width: 98%;}
.rating[data-rate="5.0"]:after{ width: 100%; }
</style>