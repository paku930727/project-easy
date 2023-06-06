<?php if (isset($component)) { $__componentOriginale2b5538aaf81eaeffb0a99a88907fd7b = $component; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\Card::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('adminlte-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(JeroenNoten\LaravelAdminLte\View\Components\Widget\Card::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="d-flex">
        <div class="mr-auto d-flex align-items-center justify-content-center">
            <h4>スケジュール</h4>
        </div>
        <div class="d-flex">
            <div class="mr-2 dropdown" wire:ignore>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ユーザーフィルター
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="dropdown-item">
                            <div class="icheck-info">
                                <input type="checkbox" id="filter_user_<?php echo e($user->id); ?>"
                                       name="selectStoreIds" value="<?php echo e($user->id); ?>"
                                       wire:model.lazy="selectedUserIds"/>
                                <label class="font-weight-normal" for="filter_user_<?php echo e($user->id); ?>">
                                    <?php echo e($user->name); ?>

                                </label>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('schedule.create', [])->html();
} elseif ($_instance->childHasBeenRendered('bVxXyxU')) {
    $componentId = $_instance->getRenderedChildComponentId('bVxXyxU');
    $componentTag = $_instance->getRenderedChildComponentTagName('bVxXyxU');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('bVxXyxU');
} else {
    $response = \Livewire\Livewire::mount('schedule.create', []);
    $html = $response->html();
    $_instance->logRenderedChild('bVxXyxU', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
        </div>
    </div>
    <div wire:loading.flex class="align-items-center justify-content-center">
        読み込み中...
    </div>
    <div id="calendar-container" wire:ignore>
        <div id="calendar"></div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2b5538aaf81eaeffb0a99a88907fd7b)): ?>
<?php $component = $__componentOriginale2b5538aaf81eaeffb0a99a88907fd7b; ?>
<?php unset($__componentOriginale2b5538aaf81eaeffb0a99a88907fd7b); ?>
<?php endif; ?>

<?php $__env->startPush('js'); ?>
    <script>
        //ドロップダウンメニュー内でクリックされてもメニューを閉じないように制御
        $('.dropdown-menu').click(function (e) {
            e.stopPropagation();
        });
        document.addEventListener('livewire:load', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                //プレミアム機能を使うためのライセンスキー(これはトライアル)
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                //表示テーマ
                themeSystem: 'bootstrap',
                //カレンダーそのものの高さ
                height: 700,
                //各ボタンの表示テキスト変更
                buttonText: {
                    resourceTimeGridDay: '日(グリッド)',
                    resourceTimelineDay: '日',
                },
                //ツールバー
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,resourceTimelineDay,resourceTimeGridDay",
                },
                //初期表示
                initialView:
                "resourceTimelineDay",
            //ドラッグなどでのイベント変更許可
            editable: true,
            //イベントをドロップした時の処理
            eventDrop: function (info) {
                window.livewire.find('<?php echo e($_instance->id); ?>').moveEvent(info);
            },
            //リソース取得
            resources: <?php echo json_encode($this->getResources(), 15, 512) ?>,
            //イベント取得
            events: function (fetchInfo, successCallback, failureCallback) {
                var start = fetchInfo.startStr;
                var end = fetchInfo.endStr;
                var timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

                window.livewire.find('<?php echo e($_instance->id); ?>').getEvents(start, end, timeZone)
                    .then(function (events) {
                        successCallback(events);
                    })
                    .catch(function () {
                        failureCallback();
                    });
            },
            //イベントの表示設定
            eventDisplay: 'block',
            //イベントのクリック処理
            eventClick: function (info) {
                console.log(info.event);
            },
        });

        calendar.render();
    });

    
</script><?php /**PATH /var/www/resources/views/livewire/schedule/index.blade.php ENDPATH**/ ?>