<aside class="w-[300px] duration-300 overflow-hidden py-4 border-e border-line bg-white" id="sidebar">
    <div>
        <div class="px-4 pb-2">
            <img src="web/static/images/BRIANlysis.png" width="157" height="33" />
        </div>
        <div class="horizontal-line"></div>
        <ul class="flex flex-col">
            <li
                class="flex items-center my-2 px-4 py-2 gap-5 hover:text-primary cursor-pointer hover:bg-secondary rounded-md">
                <i class="fa-solid fa-gauge-simple-high text-[32px]"></i>
                <a href="/dashboard" class="side-menu-list">Dashboard</a>
            </li>
            <li
                class="flex items-center my-2 px-4 py-2 gap-5 hover:text-primary cursor-pointer hover:bg-secondary rounded-md">
                <i class="fa-solid fa-list text-[32px]"></i>
                <a href="/transactions" class="side-menu-list">Transactions</a>
            </li>
            <li
                class="flex items-center my-2 px-4 py-2 gap-5 hover:text-primary cursor-pointer hover:bg-secondary rounded-md active:text-primary">
                <i class="fa-solid fa-coins text-[32px]"></i>
                <a href="/home" class="side-menu-list">Budget and Expense</a>
            </li>
            <div class="horizontal-line"></div>
            <li class="flex items-center my-2 px-4 py-2 gap-5 hover:text-primary cursor-pointer hover:bg-secondary rounded-md">
                <i class="fa-solid fa-download text-[32px]"></i>
                <a href="/index.php?page=import-form"
                    hx-get="/index.php?page=import-form"
                    hx-target="#main-content"
                    hx-swap="innerHTML"
                    class="side-menu-list">
                    Import/Export CSV file
                </a>
            </li>
        </ul>
    </div>
</aside>
