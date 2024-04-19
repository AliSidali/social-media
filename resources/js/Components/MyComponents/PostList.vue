<template>
    <div class="h-full overflow-auto flex-1 ">
        <PostItem  :post="post1" />
        <PostItem  :post="post2" />
    </div>
</template>
<script setup>
import { ref } from 'vue'
import PostItem from './PostItem.vue'
const post1 = ref({
        user: {
            id: 1,
            avatar: "https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/avatars/59/5914dc7fc6e908c1bc94f88a7bc36f524c8a1094_full.jpg",
            name: "Sidali Brahim",
        },
        group: {
            id: 1,
            name: 'Laravel Devoloper'
        },
        attachments: [
            {
                id : 1,
                image: 'image1',
                url: 'https://picsum.photos/200',
                mime: 'image/png'
            },
            {
                id : 2,
                image: 'image2',
                url: 'https://picsum.photos/200',
                mime: 'image/png'
            },
            {
                id : 3,
                name:'MyDocument.docx',
                url: '#',
                mime: 'application/msword'
            },

        ],
        body: `
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis in facere perspiciatis quidem quia, aliquam consectetu est deserunt mollitia eius ipsa quibusdam nesciunt dolor maiores, accusamus ratione optio iure enim repudiandae veniam rerum! Odio, dolores inventore commodi placeat iste officia.</p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis in facere perspiciatis quidem quia, aliquam consectetu est deserunt mollitia eius ipsa quibusdam nesciunt dolor maiores, accusamus ratione optio iure enim repudiandae veniam rerum! Odio, dolores inventore commodi placeat iste officia.</p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis in facere perspiciatis quidem quia, aliquam consectetu est deserunt mollitia eius ipsa quibusdam nesciunt dolor maiores, accusamus ratione optio iure enim repudiandae veniam rerum! Odio, dolores inventore commodi placeat iste officia.</p>
        `,
        created_at: "2024-04-04 12:08"
    });

    const post2 = ref({
        user: {
            id: 2,
            avatar: "https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/avatars/dd/ddbb9314e1e26f15ce82978d9cc5f52fddff359a_full.jpg",
            name: "Sidali Abderazak",
        },
        body: `
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis in facere perspiciatis quidem quia, aliquam consectetu est deserunt mollitia eius ip sa quibusdam nesciunt dolor maiores, accusamus ratione optio iure enim repudiandae veniam rerum! Odio.</p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis in facere perspiciatis quidem quia, aliquam consectetu est deserunt mollitia eius ip sa quibusdam nesciunt dolor maiores, accusamus ratione optio iure enim repudiandae veniam rerum! Odio, dolores inventore commodi placeat iste officia.</p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis in facere perspiciatis quidem quia, aliquam consectetu est deserunt mollitia eius ip sa quibusdam nesciunt dolor maiores, accusamus ratione optio iure enim repudiandae veniam rerum! Odio, dolores inventore commodi placeat iste officia.</p>
        `,
        created_at: "2024-04-04 12:10"
    });

</script>
