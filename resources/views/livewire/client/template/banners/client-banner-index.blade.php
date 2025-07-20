<div class="scRender">
<style>
    a {
        padding-top: 50px
    }
    .rev_slider .rev-slidebg a {
        display: block !important;
        width: 100% !important;
        aspect-ratio: 16/9 !important;
    }
    .rev_slider .rev-slidebg a img,
    .rev_slider .rev-slidebg img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        aspect-ratio: 16/9 !important;
    }
        #return-to-top i {
        color: #fff !important;
        margin: 0 !important;
        position: relative !important;
        top: -39px !important;
        font-size: 30px !important;

        -webkit-transition: all 0.3s ease !important;
        -moz-transition: all 0.3s ease !important;
        -ms-transition: all 0.3s ease !important;
        -o-transition: all 0.3s ease !important;
        transition: all 0.3s ease !important;
    }
    </style>
    <div class="prs_main_slider_wrapper">
        <div id="rev_slider_41_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container"
            data-alias="food-carousel26" data-source="gallery"
            style="margin:0px auto;padding:0px;margin-top:0px;margin-bottom:0px;">
            <div class="prs_slider_overlay"></div>
            <!-- START REVOLUTION SLIDER 5.4.1 fullwidth mode -->
            <div id="rev_slider_41_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
                <ul>
                    @foreach($banners as $banner)
                    <!-- SLIDE  -->
                    <li data-index="rs-145" data-transition="fade" data-slotamount="7" data-hideafterloop="0"
                        data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300"
                        data-rotate="0" data-saveperformance="off" data-title="{{ $banner->title }}" data-param1=""
                        data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7=""
                        data-param8="" data-param9="" data-param10="" data-description="">

                        <!-- MAIN IMAGE -->
                        @if($banner->link)
                        <a href="{{ $banner->link }}" style="display: block; width: 100%; aspect-ratio: 16/9;">
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" data-bgposition="center center" data-bgfit="cover"
                                data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina style="width: 100%; height: 100%; object-fit: cover; aspect-ratio: 16/9;">
                        </a>
                        @else
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" data-bgposition="center center" data-bgfit="cover"
                                data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina style="aspect-ratio: 16/9; width: 100%; object-fit: cover;">
                        @endif

                        <!-- LAYERS -->
                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption FoodCarousel-CloseButton rev-btn  tp-resizeme" id="slide-145-layer-5"
                            data-x="441" data-y="110" data-width="['auto']" data-height="['auto']" data-type="button"
                            data-actions='[{"event":"click","action":"stoplayer","layer":"slide-145-layer-3","delay":""},{"event":"click","action":"stoplayer","layer":"slide-145-layer-5","delay":""},{"event":"click","action":"startlayer","layer":"slide-145-layer-1","delay":""}]'
                            data-responsive_offset="on"
                            data-frames='[{"from":"z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;","speed":800,"to":"o:1;","delay":"bytrigger","ease":"Power3.easeInOut"},{"delay":"bytrigger","speed":500,"to":"auto:auto;","ease":"nothing"},{"frame":"hover","speed":"300","ease":"Power1.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(255,255,255,1);bg:rgba(41,46,49,1);bw:1px 1px 1px 1px;"}]'
                            data-textAlign="['left','left','left','left']" data-paddingtop="[14,14,14,14]"
                            data-paddingright="[14,14,14,14]" data-paddingbottom="[14,14,14,14]"
                            data-paddingleft="[16,16,16,16]" data-lasttriggerstate="reset"
                            style="z-index: 7; white-space: nowrap;border-color:transparent;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">
                            <i class="fa-icon-remove"></i>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
            </div>
        </div>
    </div>
</div>
