<div class="row">
    Barker Bob's Burger Bar - Orders:
    <ul>
    {orders}
        <li><a href="welcome/order/{ordernum}">{ordernum} ({customer})</a></li>
    {/orders}
    </ul>
    Select an order from the list above to see its receipt.
</div>