<template>
    <div class="place-photo">
        <img
            v-img:top-group
            class="place-photo__image"
            :src="photo['img_url']"
            :alt="photo.description"
        >
        <div 
            v-if="lastPhoto"
            class="place-photo__show-all"
        >
            <a 
                href="#"
                v-scroll-to="'.main'"
                @click="onClick"
            >
                {{ $t('place_page.buttons.show_all') }}
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PlacePhoto',
    props: {
        photo: {
            type: Object,
            required: true
        },
        lastPhoto: {
            type: Boolean,
            required: true
        }
    },
    methods: {
        onClick() {
            this.$emit('showAllPhotos');
        }
    }
};
</script>

<style lang="scss" scoped>

.place-photo {
    width: 200px;
    height: 200px;
    margin-right: 10px;
    overflow: hidden;
    position: relative;

    &__image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: 50% 50%;
    }

    &__show-all {
        position:absolute;
        display: flex;
        justify-content: center;
        align-items: center;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.5);
        text-align: center;
        a {
            color: white;

            &:hover {
                text-decoration: underline;
            }
        }
    }
}

</style>